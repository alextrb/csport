<?php
    namespace App\Controller;

    use App\Controller\AppController;
    use Cake\Filesystem\Folder;
    use Cake\Filesystem\File;
    use Cake\Mailer\Email;
    use Cake\ORM\Table;
    use Cake\I18n\Time;
    use Cake\I18n\Date;
    
    class ApisController extends AppController
    {   
        public function apiRegisterDevice($id_member, $id_device, $description)
        {
            $this->loadModel("Devices");
            $new = $this->Devices->newEntity();
            $new->member_id = $id_member;
            $new->serial = $id_device;
            $new->description = $description;
            $new->trusted = 0;
            $this->Devices->save($new);
            
            $this->Flash->success(__("Le device avec id: {0} a bien demandé sa validation à l'utilisateur: {1}", h($id_device), h($id_member)));
            return $this->redirect(['controller' => 'Sports', 'action' => 'index']);
        }
        
        public function apiWorkoutParameters($serial_device, $id_workout)
        {
            $this->loadModel("Workouts");
            $this->loadModel("Devices");
            $this->loadModel("Logs");
            $this->viewBuilder()->className('Json');

            $workout = $this->Workouts->findById($id_workout)->toArray();
            $device = $this->Devices->checkAuthDevice($serial_device, $workout[0]->member_id);
            
            if(count($device) > 0){
                $workouts_parameters = $this->Logs->getLogs($id_workout);
                $this->set(array(
                    'workouts_parameters' => $workouts_parameters,
                    '_serialize' => array('workouts_parameters')
                ));                
            }
            else{
                $this->Flash->error(__("Le device avec le serial : {0} ne possède pas les autorisations nécéssaires. Vous avez été redirigé vers la page d accueil", h($serial_device)));
                return $this->redirect(['controller' => 'Sports', 'action' => 'index']);
            }                 
        }

        public function apiGetSummary($serial_device)
        {
            $this->loadModel("Workouts");
            $this->loadModel("Devices");
            $this->viewBuilder()->className('Json');

            $member_id = $this->Devices->getDeviceFromSerial($serial_device)->member_id;
            $device = $this->Devices->checkAuthDevice($serial_device, $member_id);
            
            if(count($device) > 0){

                $previous_array = $this->Workouts->getPreviousWorkouts($member_id)->toArray();
                $next_array = $this->Workouts->getNextWorkout($member_id)->toArray();

                $final_array = array();

                if(count($next_array) > 0)
                {
                    array_push($final_array, $next_array[0]);
                }
                foreach($previous_array as $idw => $w)
                {
                    array_push($final_array, $previous_array[$idw]->description);
                }

                $this->set(array(
                    'final_array' => $final_array,
                    '_serialize' => array('final_array')
                ));   
            }
            else{
                $this->Flash->error(__("Le device avec le serial : {0} ne possède pas les autorisations nécéssaires. Vous avez été redirigé vers la page d accueil", h($serial_device)));
                return $this->redirect(['controller' => 'Sports', 'action' => 'index']);
            }                 
        }

        public function apiAddLog($serial_device, $id_workout, $id_member, $log_type, $log_value)
        {
            $this->loadModel("Devices");
            $this->loadModel("Logs");

            $device = $this->Devices->checkAuthDevice($serial_device, $id_member);
            if(count($device) > 0){
                $date_courante = Time::now();
                $lat = 10;
                $long = 10;
                $this->Logs->addLogs($id_member, $id_workout, $device->id, $date_courante, $lat, $long, $log_type, $log_value);
                $this->Flash->success(__("Le relevé {0} {1} a bien été ajouté avec succès à la séance {3} du membre {4}", h($log_value), h($log_type), h($id_workout), h($id_member)));
                return $this->redirect(['controller' => 'Sports', 'action' => 'index']);
            }
            else{
                $this->Flash->error(__("Le device avec le serial : {0} ne possède pas les autorisations nécéssaires. Vous avez été redirigé vers la page d accueil", h($serial_device)));
                return $this->redirect(['controller' => 'Sports', 'action' => 'index']);
            } 
        }
    }


?>