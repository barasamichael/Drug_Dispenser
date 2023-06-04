<?php
require_once("../models.php");

$contractSupply = new ContractSupply(["contractId" => 2]);
$contractSupply->save();

header("Location: ../profiles/contract_supply_profile.php");
exit;
?>
