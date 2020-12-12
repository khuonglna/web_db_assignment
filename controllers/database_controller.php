<?php
    session_start();
    require_once('../models/patient_model.php');
    require_once('../models/employee_model.php');

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = str_replace("'", "''", $data);
        return $data;
    }

    if ($_REQUEST["function"] == "getPatients") {
        // $result = array();
        $patientModel = new PatientModel();
        $patientList = $patientModel->querySearchPatient($_SESSION["name"], $_SESSION["pass"], test_input($_REQUEST["data"]));
        if ($patientList == false) {
            echo false;
        } else {
            echo json_encode($patientList);
        }
    } elseif ($_REQUEST["function"] == "searchPatients") {
        if ($_REQUEST["data"] != "") {
            // $result = array();
            $patientModel = new PatientModel();
            $patientList = $patientModel->queryGetPatientNameList(test_input($_REQUEST["data"]), $_SESSION["name"], $_SESSION["pass"]);
            if ($patientList == false) {
                echo false;
            } 
            echo json_encode($patientList);
        } else {
            echo false;
        }
    } elseif ($_REQUEST["function"] == "getDoctor") {
        $employeeModel = new EmployeeModel();
        $employeeList = $employeeModel->queryGetDoctorNameList($_SESSION["name"], $_SESSION["pass"]);
        $result = array();
        for ($i = 0; $i < sizeof($employeeList); $i++) {
            $result[$i] = array (
                "eId" => $employeeList[$i]->geteId(),
                "fName" => $employeeList[$i]->getFName(),
                "lName" => $employeeList[$i]->getLName(),
            );
        }
        echo json_encode($result);
    } elseif ($_REQUEST["function"] == "getNurse") {
        $employeeModel = new EmployeeModel();
        $employeeList = $employeeModel->queryGetNurseNameList($_SESSION["name"], $_SESSION["pass"]);
        $result = array();
        for ($i = 0; $i < sizeof($employeeList); $i++) {
            $result[$i] = array (
                "eId" => $employeeList[$i]->geteId(),
                "fName" => $employeeList[$i]->getFName(),
                "lName" => $employeeList[$i]->getLName(),
            );
        }
        echo json_encode($result);
    } elseif ($_REQUEST["function"] == "getDrugList") {
        $result = array();
        $patientModel = new PatientModel();
        $drugList = $patientModel->queryGetDrugList($_SESSION["name"], $_SESSION["pass"]);
        if ($drugList == false) {
            echo false;
        } else {
            for ($i = 0; $i < sizeof($drugList); $i++) {
                $result[$i] = array (
                    "code" => $drugList[$i]->getDrugCode(),
                    "name" => $drugList[$i]->getName(),
                    "effects" => $drugList[$i]->getEffects(),
                    "price" => $drugList[$i]->getPrice()
                );
            }
        }
        echo json_encode($result);
    } else if ($_REQUEST["function"] == "getTreatment") {
        $result = array();
        $patientModel = new PatientModel();
        $result = $patientModel->queyGetAllTreatmentByPatientId($_SESSION["name"], $_SESSION["pass"], $_REQUEST["pId"]);
        if ($result == false) {
            echo false;
        } else {
            echo json_encode($result);
        }
    } else if ($_REQUEST["function"] == "getExamination") {
        $result = array();
        $patientModel = new PatientModel();
        $result = $patientModel->queyGetAllExaminationByPatientId($_SESSION["name"], $_SESSION["pass"], $_REQUEST["pId"]);
        if ($result == false) {
            echo false;
        } else {
            echo json_encode($result);
        }
    } elseif ($_REQUEST["function"] == "addInpatient") {
        $patientModel = new PatientModel();
        $result = $patientModel->callProcAddNewInPatient(
            $_SESSION["name"], 
            $_SESSION["pass"],
            test_input($_REQUEST["fName"]),
            test_input($_REQUEST["lName"]),
            $_REQUEST["dob"],
            test_input($_REQUEST["addr"]),
            $_REQUEST["gender"],
            $_REQUEST["phone"],
            $_REQUEST["date"],
            $_REQUEST["nurseId"],
            $_REQUEST["doctorId"],
            test_input($_REQUEST["room"]),
            $_REQUEST["fee"],
            test_input($_REQUEST["diagnosis"])
        );
        if ($result != false) {
            echo json_encode($result);
        } else {
            echo false;
        }
    } elseif ($_REQUEST["function"] == "addOutpatient") {
        $patientModel = new PatientModel();
        $result = $patientModel->callProcAddNewOutPatient(
            $_SESSION["name"], 
            $_SESSION["pass"],
            test_input($_REQUEST["fName"]),
            test_input($_REQUEST["lName"]),
            $_REQUEST["dob"],
            test_input($_REQUEST["addr"]),
            $_REQUEST["gender"],
            $_REQUEST["phone"],
            $_REQUEST["examDate"],
            $_REQUEST["secondDate"],
            $_REQUEST["doctorId"],
            $_REQUEST["fee"],
            test_input($_REQUEST["diagnosis"])
        );
        if ($result == false) {
            echo false;
        } else {
            echo json_encode($result);
        }
    } elseif ($_REQUEST["function"] == "addDrug") {
        $patientModel = new PatientModel();
        $result = $patientModel->callProcNewTExaminationMedication(
            $_SESSION["name"], 
            $_SESSION["pass"],
            $_REQUEST["pId"],
            $_REQUEST["examId"],
            $_REQUEST["code"],
            $_REQUEST["amount"]
        );
        if ($result == true) {
            echo true;
        } else {
            echo false;
        }
    } elseif ($_REQUEST["function"] == "addDrugTreatment") {
        $patientModel = new PatientModel();
        $result = $patientModel->callProcNewTreatmentMedication(
            $_SESSION["name"], 
            $_SESSION["pass"],
            $_REQUEST["aId"],
            $_REQUEST["tId"],
            $_REQUEST["code"],
            $_REQUEST["amount"]
        );
        if ($result == true) {
            echo true;
        } else {
            echo false;
        }
    } elseif ($_REQUEST["function"] == "getPatientByDoctor") {
        $patientModel = new PatientModel();
        $result = $patientModel->queryGetPatientsByDoctorId(
            $_SESSION["name"], 
            $_SESSION["pass"],
            $_REQUEST["dId"]
            );
        
        if ($result != false) {
            echo json_encode($result);
        } else {
            echo false;
        }
    }else {
        echo json_encode("nothing");
    }

?>