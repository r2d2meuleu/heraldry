<?php

namespace Heraldry\Component;

class Field {
    private $division = false;
    private $elements;
    private $type;
    private $width;
    private $height;

    public function __construct( $type, $width, $height, $tincture1, $tincture2 = false ) {
        $this->type = $type;
        $this->width = $width;
        $this->height = $height;
        $this->tincture1 = $tincture1;
        $this->tincture2 = $tincture2;

        $this->addBase();

        if ( $this->type != 'plain' ) {
            $this->addDivision();
        }
    }

    public function addBase() {
        $this->elements[] = [
            'name' => 'rect',
            'attributes' => [
                'x' => '0',
                'y' => '0',
                'width' => $this->width,
                'height' => $this->height,
                'style' => 'fill:' . $this->tincture1->getCode(),
                'mask' => 'url(#shieldmask)',
            ]
        ];
    }

    public function addDivision() {
        $divisionClass = 'Heraldry\\Component\\Division\\' . $this->type;
        $this->division = new $divisionClass;
    }

    public function getBlazon() {
        if ( $this->type == 'plain' ) {
            return $this->tincture1->getName();
        } else {
            return $this->division->getBlazon() . ' ' . $this->tincture1->getName() . ' and ' . $this->tincture2->getName();
        }
    }

    public function getElements() {
        $elements = $this->elements;

        if ( $this->division ) {
            $divisionElements = $this->division->getElements( $this->tincture2, $this->width, $this->height );
            foreach ( $divisionElements as $divisionElement ) {
                $elements[] = $divisionElement;
            }
        }

        return $elements;
    }
}