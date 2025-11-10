<?php
require_once(__DIR__ . '/../core/DB.php');
require_once(__DIR__ . '/ProductEntity.php');
class ProductModel
{
    private DB $db;
    private const TableName = "products";

    public function __construct()
    {
        $this->db = new DB();
    }

    public function createProduct(string $name, float $price, string $description, string|NULL $image = NULL): int
    {
        $insertProduct[] = (new ProductEntity($name, $price, $description, $image))->getAproductInfo();
        $productID = $this->db->insert(self::TableName, [
            'name' => $insertProduct['name'],
            'price' => $insertProduct['price'],
            'description' => $insertProduct['description'],
            'image' => $insertProduct['image']
        ]);
        return $productID;
    }



    public function getAllProducts(array|NULL $conditions = NULL, int|NULL $limit = NULL, int|NULL $offset = NULL): array
    {

        $products = $this->db->selectAll(self::TableName, $conditions, $limit, $offset);
        foreach ($products as $product) {
            $productsList[] = (new ProductEntity(
                $product['name'],
                $product['price'],
                $product['description'],
                $product['image'],
                $product['id']
            ))->getAproductInfo();
        }
        return $productsList;
    }

    public function getAProduct(array $conditions, int|NULL $limit = NULL, int|NULL $offset = NULL): array
    {

        $product = $this->db->selectOne(self::TableName, $conditions, $limit, $offset);
        $productInfo[] = (new ProductEntity(
            $product['name'],
            $product['price'],
            $product['description'],
            $product['image'],
            $product['id']
        ))->getAproductInfo();
        return $productInfo;
    }

    public function updateProduct(array $product): void
    {
        $updateProduct[] = new ProductEntity(
            $product['name'],
            $product['price'],
            $product['description'],
            $product['image'],
            $product['id']
        );
        $this->db->update(self::TableName, $updateProduct, ['id' => $updateProduct['id']]);
    }

    public function deleteProduct(int $id): void
    {
        $this->db->delete(self::TableName, ['id' => $id]);
    }
}
