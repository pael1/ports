<?php
//(Here the App\Repositories is the folder name)
namespace App\Repositories;

interface IComplaint
{
   public function all();

   public function fetchDataDataTables(array $complaints);

   public function getProsecutors();

   public function getOffices();

   public function getUsers($designation);

   public function getParties($party, $id);

   public function getViolatedLaws($id);

   public function getAttachments($id);

   public function getCases($id);

   public function checkRelatedParty(array $data);

   public function checkViolatedLaw(array $data);

   public function getComplaintId(array $data);

   public function get($id);

   public function store(array $data);

   public function update($id, array $data); 

   public function deleteComplaint($tableName, $tableFieldName, $id);
}