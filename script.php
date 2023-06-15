<?php
$filename = 'input.json';
$jsonData = file_get_contents($filename);
$data = json_decode($jsonData, true);
$requiredIncome = $data['required_income'];
$smsList = $data['sms_list'];
usort($smsList, function ($b, $a) {
    return $a['price'] > $b['price'];
});
$limitedList = array_slice($smsList, $requiredIncome);

function findOptimalSmsPlan(array $smsList, float $requiredIncome): array
{
    $smsPlanList = [];
    backtracking($smsList, $requiredIncome, 0, $smsPlanList);
    return $smsPlanList;
}
function backtracking(array $smsList, float $requiredIncome, float $currentSum, array &$smsPlanList): array
{
    if ($currentSum === $requiredIncome) {
        return $smsPlanList;
    }
    foreach ($smsList as $key => $value) {
        $array = array_keys($smsList);
        if ($currentSum + $value['income'] <= $requiredIncome) {
            $currentSum += $value['income'];
            $smsPlanList[] = $value['price'];
            return backtracking($smsList, $requiredIncome, $currentSum, $smsPlanList);
        } elseif (($key == end($array) && $currentSum + $value['income'] >= $requiredIncome)) {
            $currentSum += $value['income'];
            $smsPlanList[] = $value['price'];
        }
    }
    return $smsPlanList;
}
$smsPlan = findOptimalSmsPlan($smsList, $requiredIncome);
echo json_encode($smsPlan);
?>