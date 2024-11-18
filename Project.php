<?php
class Project {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function create($userId, $data, $image) {
        $targetDir = "images/projects/";
        if (!file_exists("images")) {
            mkdir("images", 0777, true);
        }
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = basename($image["name"]);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $newFileName = time() . '_' . uniqid() . '.' . $fileExtension;
        $targetFilePath = $targetDir . $newFileName;

        if ($this->validateImage($fileExtension) && move_uploaded_file($image["tmp_name"], $targetFilePath)) {
            $stmt = $this->db->prepare("INSERT INTO proyek (user_id, NAMA, DESKRIPSI, DESKRIPSI_DETAIL, SDGTAG, FOTO_PROYEK, contact_email, contact_phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssssss", $userId, $data['projectName'], $data['projectDescription'], 
                            $data['detailed_description'], $data['sdgTag'], $targetFilePath, 
                            $data['contact_email'], $data['contact_phone']);
            return $stmt->execute();
        }
        return false;
    }
    
    public function update($projectId, $userId, $data, $image = null) {
        $project = $this->getProject($projectId, $userId);
        if (!$project) return false;
        
        $targetFilePath = $project['FOTO_PROYEK'];
        
        if ($image && $image['size'] > 0) {
            $targetDir = "images/projects/";
            $newFileName = time() . '_' . uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
            $targetFilePath = $targetDir . $newFileName;
            
            if ($this->validateImage(pathinfo($image['name'], PATHINFO_EXTENSION)) && 
                move_uploaded_file($image['tmp_name'], $targetFilePath)) {
                unlink($project['FOTO_PROYEK']);
            }
        }
        
        $stmt = $this->db->prepare("UPDATE proyek SET NAMA = ?, DESKRIPSI = ?, DESKRIPSI_DETAIL = ?, 
                                   SDGTAG = ?, FOTO_PROYEK = ?, contact_email = ?, contact_phone = ? 
                                   WHERE ID_PROYEK = ? AND user_id = ?");
        $stmt->bind_param("sssssssii", $data['projectName'], $data['projectDescription'], 
                         $data['detailed_description'], $data['sdgTag'], $targetFilePath, 
                         $data['contact_email'], $data['contact_phone'], $projectId, $userId);
        return $stmt->execute();
    }
    
    public function delete($projectId, $userId) {
        $project = $this->getProject($projectId, $userId);
        if (!$project) return false;
        
        $stmt = $this->db->prepare("DELETE FROM proyek WHERE ID_PROYEK = ? AND user_id = ?");
        $stmt->bind_param("ii", $projectId, $userId);
        
        if ($stmt->execute()) {
            if (file_exists($project['FOTO_PROYEK'])) {
                unlink($project['FOTO_PROYEK']);
            }
            return true;
        }
        return false;
    }
    
    public function getProject($projectId, $userId = null) {
        $sql = "SELECT p.*, u.username FROM proyek p JOIN users u ON p.user_id = u.id WHERE p.ID_PROYEK = ?";
        $params = [$projectId];
        $types = "i";
        
        if ($userId !== null) {
            $sql .= " AND p.user_id = ?";
            $params[] = $userId;
            $types .= "i";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    public function getAllProjects() {
        $sql = "SELECT p.*, u.username FROM proyek p JOIN users u ON p.user_id = u.id ORDER BY p.ID_PROYEK DESC";
        return $this->db->query($sql);
    }
    
    public function getUserProjects($userId) {
        $stmt = $this->db->prepare("SELECT * FROM proyek WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    private function validateImage($extension) {
        $allowTypes = array('jpg', 'jpeg', 'png', 'gif');
        return in_array(strtolower($extension), $allowTypes);
    }
} 