<?php
/**
 * Created by PhpStorm.
 * User: Piotr
 * Date: 2017-08-03
 * Time: 21:45
 */
namespace AppBundle\Table\Column\Custom;

use JGM\TableBundle\Table\Column\AbstractColumn;
use JGM\TableBundle\Table\Row\Row;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\OptionsResolver\OptionsResolver;

class searchButtonsColumn extends AbstractColumn
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        // Add a new option named 'alt_image' to the existing options 'label', 'attr', etc
        $optionsResolver->setDefaults(array(
            'conditions' => [
                'sendInvitation' => function($value){return true;},
                'confirmInvitation' => function($value){return true;},
                'cancelInvitation' => function($value){return true;},
                'removeFriend' => function($value){return true;},
            ]
        ));
    }

    public function getContent(Row $row)
    {

        $value = $this->getValue($row); // Returns the value of the property with the same name as the column at this row.

        $html = "";

        if($this->options['conditions']['sendInvitation']($value)) {
            $html .= "<button id={$value} class='btn btn-info sendInvitation'> <i class=\"fa fa-paper-plane-o\" aria-hidden=\"true\"></i> Send invitation </button><br>";
        }

        if($this->options['conditions']['confirmInvitation']($value)) {
            $html .= "<button id={$value} class='btn btn-success confirmInvitation'> <i class=\"fa fa-check\" aria-hidden=\"true\"></i> Confirm invitation</button><br>";
        }

        if($this->options['conditions']['cancelInvitation']($value)) {
            $html .= "<button id={$value} class='btn btn-warning removeFriend'> <i class=\"fa fa-times\" aria-hidden=\"true\"></i> Cancel invitation</button><br>";
        }

        if($this->options['conditions']['removeFriend']($value)) {
            $html .= "<button id={$value} class='btn btn-danger removeFriend'> <i class=\"fa fa-times\" aria-hidden=\"true\"></i> Remove friend</button><br>";
        }

        return $html;
    }
}