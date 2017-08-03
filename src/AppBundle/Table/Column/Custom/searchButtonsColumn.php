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
            'friends' => [],
        ));
    }

    public function getContent(Row $row)
    {

        $value = $this->getValue($row); // Returns the value of the property with the same name as the column at this row.

        $html = "";

        $html .= "<button id={$value} class='btn btn-info sendInvitation'> <i class=\"fa fa-paper-plane-o\" aria-hidden=\"true\"></i> Send invitation </button>";
        $html .= "<button id={$value} class='btn btn-danger removeFriend'> <i class=\"fa fa-times\" aria-hidden=\"true\"></i> Remove friend</button>";


//        if($this->options['countButtons'] > 1){
//            for($i = 0; $i < $this->options['countButtons']; $i++)
//            {
//                $label = !empty($this->options['label_btn'][$i]) ? $this->options['label_btn'][$i] : "";
//                $class = !empty($this->options['attr_btn']['class'][$i]) ? "class='{$this->options['attr_btn']['class'][$i]}'" : "";
//                $style = !empty($this->options['attr_btn']['style'][$i]) ? "style='{$this->options['attr_btn']['style'][$i]}'" : "";
//                $other = !empty($this->options['attr_btn']['other'][$i]) ? $this->options['attr_btn']['other'][$i] : "";
//
//
//                $html ."<button id={$value} class='{$class}' style='{$style}' {$other}> {$label}</button>";
//            }
//        }else {
//            $label = !empty($this->options['label_btn']) ? $this->options['label_btn'] : "";
//            $class = !empty($this->options['attr_btn']['class']) ? "class='{$this->options['attr_btn']['class']}'" : "";
//            $style = !empty($this->options['attr_btn']['style']) ? "style='{$this->options['attr_btn']['style']}'" : "";
//            $other = !empty($this->options['attr_btn']['other']) ? $this->options['attr_btn']['other'] : "";
//
//            $html = "<button id={$value} {$class} {$style} {$other}> {$label}</button>";
//        }
//        "<button value={$value} class='{$class}' style='{$style}' > {$label}</button>";

        return $html;

    }
}