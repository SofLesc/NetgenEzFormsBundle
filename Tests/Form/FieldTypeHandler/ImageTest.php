<?php

namespace Netgen\Bundle\EzFormsBundle\Tests\Form\FieldTypeHandler;

use eZ\Publish\Core\Repository\Values\ContentType\FieldDefinition;
use Netgen\Bundle\EzFormsBundle\Form\FieldTypeHandler;
use Netgen\Bundle\EzFormsBundle\Form\FieldTypeHandler\Image;
use eZ\Publish\Core\FieldType\Image\Value as ImageValue;
use Symfony\Component\Form\FormBuilder;

class ImageTest extends \PHPUnit_Framework_TestCase
{
    public function testAssertInstanceOfFieldTypeHandler()
    {
        $image = new Image();

        $this->assertInstanceOf(FieldTypeHandler::class, $image);
    }

    public function testConvertFieldValueToForm()
    {
        $image = new Image();
        $imageValue = new ImageValue(array('fileName' => 'picture.jpeg'));

        $returnedValue = $image->convertFieldValueToForm($imageValue);

        $this->assertNull($returnedValue);
    }

    public function testConvertFieldValueFromForm()
    {
        $image = new Image();
        $data = $this->getMockBuilder(\SplFileInfo::class)
            ->disableOriginalConstructor()
            ->setMethods(array('getRealPath', 'getClientOriginalName', 'getSize'))
            ->getMock();

        $data->expects($this->once())
            ->willReturn('/some/path')
            ->method('getRealPath');

        $data->expects($this->once())
            ->willReturn('image.jpg')
            ->method('getClientOriginalName');

        $data->expects($this->once())
            ->willReturn(123)
            ->method('getSize');

        $returnedData = $image->convertFieldValueFromForm($data);

        $this->assertInstanceOf(ImageValue::class, $returnedData);
    }

    public function testConvertFieldValueFromFormWhenDataIsNull()
    {
        $image = new Image();
        $returnedData = $image->convertFieldValueFromForm(null);

        $this->assertNull($returnedData);
    }

    public function testBuildFieldCreateForm()
    {
        $formBuilder = $this->getMockBuilder(FormBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(array('add'))
            ->getMock();

        $formBuilder->expects($this->exactly(2))
            ->method('add');

        $fieldDefinition = new FieldDefinition(
            array(
                'id' => 'id',
                'identifier' => 'identifier',
                'isRequired' => true,
                'descriptions' => array('fre-FR' => 'fre-FR'),
                'names' => array('fre-FR' => 'fre-FR'),
            )
        );

        $languageCode = 'eng-GB';

        $image = new Image();
        $image->buildFieldCreateForm($formBuilder, $fieldDefinition, $languageCode);
    }
}
