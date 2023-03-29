<?php

class DB
{
    private PDO $conn;

    public function __construct($dsn, $username, $password)
    {
        try {
            $this->conn = new PDO($dsn, $username, $password);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function logVisit()
    {
        if ($this->checkForExistingRecord())
        {
            $this->updateRow();
        } else {
            $this->insertRow();
        }
    }

    private function checkForExistingRecord()
    {
        $sql = "SELECT * 
		        FROM visits
		        WHERE ip_address = INET_ATON(:ip) AND user_agent = :ua AND page_url = :url";

        $stmt = $this->conn->prepare($sql);

        list($ip, $agent, $url) = $this->getParams($_SERVER);

        $stmt->bindParam(':ip', $ip);
        $stmt->bindParam(':ua', $agent);
        $stmt->bindParam(':url', $url);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateRow()
    {
        $sql = "UPDATE visits 
                SET view_date = now(), views_count = views_count + 1 
                WHERE ip_address = INET_ATON(:ip) AND user_agent = :ua AND page_url = :url";
        list($ip, $agent, $url) = $this->getParams($_SERVER);

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ip', $ip);
        $stmt->bindParam(':ua', $agent);
        $stmt->bindParam(':url', $url);
        $stmt->execute();
    }
    public function insertRow()
    {
        $sql = "INSERT INTO visits(ip_address, user_agent, page_url) 
                VALUES(INET_ATON(:ip), :ua, :url)";

        $stmt = $this->conn->prepare($sql);
        list($ip, $agent, $url) = $this->getParams($_SERVER);
        $stmt->bindParam(':ip', $ip);
        $stmt->bindParam(':ua', $agent);
        $stmt->bindParam(':url', $url);
        $stmt->execute();
    }

    private function getParams($params)
    {
        return [
            $params['REMOTE_ADDR'],
            $params['HTTP_USER_AGENT'],
            $params['HTTP_REFERER'],
        ];
    }
}

