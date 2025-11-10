<?php
require_once(__DIR__ . '/../core/DataHandling.php');
class ProductEntity
{
    //attributs
    private int|NULL $id;
    private string $name;
    private float $price;
    private string $description;
    private string $image;
    //logique metier
    private const NAME_MIN_LENGTH = 3;
    private const NAME_MAX_LENGTH = 100;
    private const DESC_MIN_LENGTH = 10;
    private const DESC_MAX_LENGTH = 1000;
    private const PRICE_MIN = 0;
    private const DEFAULT_IMAGE_URL = "/public/images/default.png";

    function __construct(string $name, float $price, string $description, string|NULL $image = NULL, int|NULL $id = NULL)
    {
        $this->setAproductInfo($name, $price, $description, $image);
        $this->id = $id;
    }

    public function getAproductInfo(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
            'image' => $this->image
        ];
    }

    private function setAproductInfo(string $name, float $price, string $description, string|NULL $image = NULL): void
    {
        if (!DataHandling::validateStringLength($name, self::NAME_MIN_LENGTH, self::NAME_MAX_LENGTH)) {
            throw new Error("Name is too short minimum or exceed maximum
            length is " . $this::NAME_MIN_LENGTH . "to" . $this::NAME_MAX_LENGTH);
        }
        if (!DataHandling::validateStringLength($description, self::DESC_MIN_LENGTH, self::DESC_MAX_LENGTH)) {
            throw new Error("DESC is too short minimum or exceed maximum
            length is " . $this::DESC_MIN_LENGTH . "to" . $this::DESC_MAX_LENGTH);
        }
        if (!DataHandling::validateFloatMin($price, self::PRICE_MIN)) {
            throw new Error("Price is below minimum" . $this::PRICE_MIN);
        }
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->image = DataHandling::getDefaultImageUrl($image, self::DEFAULT_IMAGE_URL);
    }
}

// syntaxe objet facile new ProductEntity(price:89,description:"sjh",name:"zije");
