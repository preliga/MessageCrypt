<?php
/**
 * Created by PhpStorm.
 * User: Piotr
 * Date: 2017-07-31
 * Time: 22:15
 */

namespace AppBundle\Table\Column;

use JGM\TableBundle\Table\Column\AbstractColumn;
use JGM\TableBundle\Table\Row\Row;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ButtonColumn extends AbstractColumn
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        // Add a new option named 'alt_image' to the existing options 'label', 'attr', etc
        $optionsResolver->setDefaults(array(
            'attr_btn' => [],
            'countButtons' => 1,
            'label_btn' => "",
        ));
    }

    public function getContent(Row $row)
    {
        $value = $this->getValue($row); // Returns the value of the property with the same name as the column at this row.

        $html = "";

        if($this->options['countButtons'] > 1){
            for($i = 0; $i < $this->options['countButtons']; $i++)
            {
                $label = !empty($this->options['label_btn'][$i]) ? $this->options['label_btn'][$i] : "";
                $class = !empty($this->options['attr_btn']['class'][$i]) ? "class='{$this->options['attr_btn']['class'][$i]}'" : "";
                $style = !empty($this->options['attr_btn']['style'][$i]) ? "style='{$this->options['attr_btn']['style'][$i]}'" : "";
                $other = !empty($this->options['attr_btn']['other'][$i]) ? $this->options['attr_btn']['other'][$i] : "";


                $html ."<button id={$value} class='{$class}' style='{$style}' {$other}> {$label}</button>";
            }
        }else {
            $label = !empty($this->options['label_btn']) ? $this->options['label_btn'] : "";
            $class = !empty($this->options['attr_btn']['class']) ? "class='{$this->options['attr_btn']['class']}'" : "";
            $style = !empty($this->options['attr_btn']['style']) ? "style='{$this->options['attr_btn']['style']}'" : "";
            $other = !empty($this->options['attr_btn']['other']) ? $this->options['attr_btn']['other'] : "";

            $html = "<button id={$value} {$class} {$style} {$other}> {$label}</button>";
        }
//        "<button value={$value} class='{$class}' style='{$style}' > {$label}</button>";

        return $html;

    }
}