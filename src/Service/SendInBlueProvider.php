<?php

namespace App\Service;

use SendinBlue\SendinBlueApiBundle\Wrapper\Mailin;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Description of SendInBlueProvider.
 *
 * @author aymane
 */
class SendInBlueProvider
{
    const API_ERR_OUT_OF = "Erreur, il semble que l'envoi d'email soit impossible";
    const API_SUCCESS_GET_NUM = "Un email vous a été envoyé avec votre numéro de suivi";

    /**
     *
     * @var Object
     */
    private $mailer;

    /**
     * @var string
     */
    private $kernelEnv;

    /**
     * @var string
     */
    private $deliveryAddress;

    function __construct(Mailin $mailer, $kernelEnv, $deliveryAddress)
    {
        $this->mailer = $mailer;
        $this->kernelEnv = $kernelEnv;
        $this->deliveryAddress = $deliveryAddress;
    }

    /**
     * Description of $option
     * $option = array( "id" => 2,
     * "to" => "to-example@example.net|to2-example@example.net",
     * "cc" => "cc-example@example.net'",
     * "bcc" => "bcc-example@example.net",
     * "replyto" => "replyto-example@example.net",
     * "attr" => array("EXPEDITEUR"=>"His name","SUBJECT"=>"This is my subject"),
     * "attachment_url" => "",
     * "attachment" => array("myfilename.pdf" => "your_pdf_files_base64_encoded_chunk_data"),
     * "headers" => array("Content-Type"=> "text/html;charset=iso-8859-1", "X-param1"=> "value1", "X-param2"=> "value2", "X-Mailin-custom"=>"my custom value","X-Mailin-tag"=>"my tag value")
     * );
     * @param array $option
     * @return boolean
     */
    public function sendEmailWithTemplate(array $option)
    {
        if ($option == null) {
            return false;
        }
        if ($this->kernelEnv === 'dev' && $this->deliveryAddress) {
            $option['to'] = $this->deliveryAddress;
        }
        $result = $this->mailer->send_transactional_template($option);

        return $result;
    }

    /**
     * Send email to $to.
     *
     * @param array $options
     * @param array|null $values
     *
     * @return bool
     */
    public function sendEmailByOptions(array $options, array $values = null)
    {
        foreach ($options as $key => $option) {
            $options[$key] = $this->replaceVariable($option, $values);
        }

        return $this->sendEmailWithTemplate($options);
    }

    /**
     * Replace parameters.
     *
     * @param $subject
     * @param $values
     *
     * @return mixed
     */
    private function replaceVariable($subject, $values)
    {
        return preg_replace_callback('/\#(([A-Za-z.])+)\#/', function($matches) use ($values) {
            $variables = explode('.', $matches[1]);
            if (!isset($values[$variables[0]])) {
                return;
            }
            if (isset($variables[1])) {
                $accessor = PropertyAccess::createPropertyAccessor();

                return $accessor->getValue( $values[$variables[0]], $variables[1]);
            } else {
                return $values[$variables[0]];
            }
        }, $subject);
    }
}

