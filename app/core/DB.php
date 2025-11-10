<?php
class DB
{
    private const DBNAME = "app-database";
    private const DBUSER = "root";
    private const DBPSSWD = "root"; //password
    private const DBHOST = "bdd";

    private function connect(): PDO
    {
        try {
            $pdo = new PDO("mysql:host=" . $this::DBHOST . ";dbname=" . $this::DBNAME, $this::DBUSER, $this::DBPSSWD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            console("Connection failed: " . $e->getMessage());
            die();
        }
    }

    private function disconnect(PDO $pdo): void
    {
        $pdo = null;
    }

    private function handleColVal(array $tableColVal = [])
    {
        // Récupère les noms des colonnes
        $cols = implode(", ", array_keys($tableColVal));

        // Crée autant de "?" que de valeurs
        $placeholders = implode(", ", array_fill(0, count($tableColVal), "?"));

        return [$cols, $placeholders];
    }
    ////fonction publique crud prête à être appelée/////
    public function insert(string $tableName, array $tableColVal = [])
    {
        return $this->prepareInsert($tableName, $tableColVal);
    }
    //peut aussi être utilisé pour selectionné une seule donnée
    public function selectAll(string $tableName, array|NULL $conditions = NULL, int|NULL $limit = null, int|NULL $offset=NULL): array
    {
        return $this->prepareSelectAll($tableName, $conditions, $limit, $offset);
    }

     public function selectOne(string $tableName, array|NULL $conditions = NULL, int|NULL $limit = null,  int|NULL $offset): array
    {
        return $this->prepareSelectOne($tableName, $conditions, $limit, $offset);
    }

    public function update(string $tableName,array $tableColVal = [], $conditions = [])
    {
       return $this->prepareUpdate($tableName, $tableColVal, $conditions);
    }

    public function delete(string $tableName, array $conditions = [])
    {
       return $this->prepareDelete($tableName, $conditions);
    }
    public function anonymize(string $tableName, array $tableColVal=[], array $conditions=[]){
        $this->prepareAnonymizeData($tableName, $tableColVal, $conditions);
    }
    ////fonction privée qui prépare les requêtes////
    private function prepareInsert(string $tableName, $tableColVal): int
    {
        $pdo = $this->connect();

        [$cols, $placeholders] = $this->handleColVal($tableColVal);

        // prépare la requête sql
        $sql = "INSERT INTO $tableName ($cols) VALUES ($placeholders)";

        $stmt = $pdo->prepare($sql);

        // ⬇Bind les valeurs dans l'ordre (sans les clés)
        $stmt->execute(array_values($tableColVal));

        if ($stmt->rowCount() > 0) {
            console("Insert into $tableName successful.");
        } else {
            console("Failure inserting into $tableName.");
        }
        $id=$pdo->lastInsertId();
        $this->disconnect($pdo);
        return $id;
    }

    private function prepareSelectAll(string $tableName, array|NULL $conditions = NULL, int|NULL $limit = NULL, int|NULL $offset = null): array
    {
        $pdo = $this->connect();
        $sql = "SELECT * FROM $tableName";

        // WHERE
        if (!empty($conditions) || $conditions !==NULL) {
            $whereClauses = [];
            foreach ($conditions as $col => $val) {
                $whereClauses[] = "$col = ?";
            }
            $whereSql = implode(" AND ", $whereClauses);
            $sql .= " WHERE $whereSql";
        }

        // LIMIT et OFFSET
        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit;

            if ($offset !== null) {
                $sql .= " OFFSET " . (int)$offset;
            }
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_values($conditions));

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->disconnect($pdo);

        if (empty($results)) {
            console("No records found in $tableName.");
        } else {
            console("Records retrieved from $tableName.");
        }

        return $results;
    }

        private function prepareSelectOne(string $tableName, array|NULL $conditions = NULL, int|NULL $limit = NULL, int|NULL $offset = null): array
    {
        $pdo = $this->connect();
        $sql = "SELECT * FROM $tableName";

        // WHERE
        if (!empty($conditions) || $conditions !==NULL) {
            $whereClauses = [];
            foreach ($conditions as $col => $val) {
                $whereClauses[] = "$col = ?";
            }
            $whereSql = implode(" AND ", $whereClauses);
            $sql .= " WHERE $whereSql";
        }

        // LIMIT et OFFSET
        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit;

            if ($offset !== null) {
                $sql .= " OFFSET " . (int)$offset;
            }
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_values($conditions));

        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->disconnect($pdo);

        if (empty($results)) {
            console("No records found in $tableName.");
        } else {
            console("Records retrieved from $tableName.");
        }

        return $results;
    }


    private function prepareUpdate(string $tableName, array $tableColVal = [], array $conditions = []): void
    {
        $pdo = $this->connect();

        // Construction de la partie SET
        $setClauses = [];
        foreach ($tableColVal as $col => $val) {
            $setClauses[] = "$col = ?";
        }
        $setSql = implode(", ", $setClauses);

        $sql = "UPDATE $tableName SET $setSql";

        // Construction de la partie WHERE
        if (!empty($conditions)) {
            $whereClauses = [];
            foreach ($conditions as $col => $val) {
                $whereClauses[] = "$col = ?";
            }
            $whereSql = implode(" AND ", $whereClauses);
            $sql .= " WHERE $whereSql";
        } 
        else {
            console("Update aborted: No conditions provided for $tableName.");
            return;
        }

        $stmt = $pdo->prepare($sql);

        // IMPORTANT : il faut combine les valeurs SET + WHERE dans l'ordre, c'est fait grâce à cet array merge
        $allValues = array_merge(array_values($tableColVal), array_values($conditions));
        $stmt->execute($allValues);

        if ($stmt->rowCount() > 0) {
            console("Update in $tableName successful. Rows affected: " . $stmt->rowCount());
        } else {
            console("No rows updated in $tableName.");
        }

        $this->disconnect($pdo);
    }

    private function prepareDelete(string $tableName, $conditions = []): void
    {
        $pdo = $this->connect();

        $sql = "DELETE FROM $tableName";

        // WHERE
        if (!empty($conditions)) {
            $whereClauses = [];
            foreach ($conditions as $col => $val) {
                $whereClauses[] = "$col = ?";
            }
            $whereSql = implode(" AND ", $whereClauses);
            $sql .= " WHERE $whereSql";
        } else {
            console("Delete aborted: No conditions provided for $tableName.");
            return;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_values($conditions));

        if ($stmt->rowCount() > 0) {
            console("Delete from $tableName successful. Rows affected: " . $stmt->rowCount());
        } else {
            console("No rows deleted from $tableName.");
        }

        $this->disconnect($pdo);
    }

    private function prepareAnonymizeData(string $tableName, array $tableColVal=[], array $conditions=[]):void{
        $pdo = $this->connect();

        // Construction de la partie SET
        $setClauses = [];
        foreach ($tableColVal as $col => $val) {
            $setClauses[] = "$col = ?";
            $tableColVal[$val]="ANONYMISED";
        }
        $setSql = implode(", ", $setClauses);

        $sql = "UPDATE $tableName SET $setSql";

        // Construction de la partie WHERE
        if (!empty($conditions)) {
            $whereClauses = [];
            foreach ($conditions as $col => $val) {
                $whereClauses[] = "$col = ?";
            }
            $whereSql = implode(" AND ", $whereClauses);
            $sql .= " WHERE $whereSql";
        } 
        else {
            console("Update aborted: No conditions provided for $tableName.");
            return;
        }

        $stmt = $pdo->prepare($sql);

        $allValues = array_merge(array_values($tableColVal), array_values($conditions));
        $stmt->execute($allValues);

        if ($stmt->rowCount() > 0) {
            console("Update in $tableName successful. Rows affected: " . $stmt->rowCount());
        } else {
            console("No rows updated in $tableName.");
        }

        $this->disconnect($pdo);

    }

}

?>