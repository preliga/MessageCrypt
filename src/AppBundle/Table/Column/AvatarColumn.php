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
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvatarColumn extends AbstractColumn
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        // Add a new option named 'alt_image' to the existing options 'label', 'attr', etc
        $optionsResolver->setDefaults(array(
            'alt_image' => 'not_found.png',
            'alt_text' => 'Image not found'
        ));
    }

    public function getContent(Row $row)
    {
        $value = $this->getValue($row); // Returns the value of the property with the same name as the column at this row.

        if ($value === null || (is_string($value) && strlen($value) === 0)) {
            $value = $this->options['alt_image']; // Get the value of the option 'alt_image', whose default is 'not_found.png'
        }

        $path = "uploads/avatars/$value";
        if(!file_exists($path)){
            return "<img src='/images/empty.png' alt='{$this->options['alt_text']}' width='100px'/>";
        } else {
            return "<img src='/$path' alt='{$this->options['alt_text']}' width='100px'/>";
        }

    }
}