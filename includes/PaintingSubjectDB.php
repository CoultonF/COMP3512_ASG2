<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'On');



class PaintingSubjectDB extends AbstractDB{
protected $baseSQL = "SELECT PaintingSubjectID, PaintingID, SubjectID FROM PaintingSubjects";
private $connection = null;
protected $keyFieldName = "PaintingSubjectID";

public function __construct($connection) {
    parent::__construct($connection);
}

public function getByPaintingID ($id) {
$sql = "SELECT SubjectID FROM PaintingSubjects WHERE PaintingID = ?";
$result = DBHelper::runQuery($this->getConnection(), $sql, Array($id));
$subjectids = array();

foreach ($result as $subject) {
array_push($subjectids, $subject['SubjectID']);
}

return $subjectids;
}


}