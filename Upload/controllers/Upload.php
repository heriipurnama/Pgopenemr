<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Upload extends MX_Controller { 
      function index(){
        //echo "string";
        $username = $this->session->userdata("username");
        $has_privilege = has_privilege($username,$this->uri->segment(1));
        //load language
        $lang = $this->session->userdata("lang")?$this->session->userdata("lang"):getPref('_DEFAULT_LANG');
        $this->lang->load('system', $lang); 
        $module_name = $this->uri->segment(1);
        //$has_privilege = true;
        if($has_privilege){
          $data['title'] = "Upload";
          $this->load->view("upload_view",$data);
         }
        else{     
          redirect('login');
        }
      }

      function setSearch() {
        $this->session->set_userdata('umSearchUser', $_REQUEST['user']);
        //echo "ok";
      }

      function add(){ 
          $username = $this->session->userdata("username");
          $temp = explode(".", $_FILES['image']['name']);
          $name = $username.'_'.date("Ymd").'_'.$_FILES['image']["name"];
          //$config['upload_path'] = './uploads/';
          $config['allowed_types'] = '*';
          $config['max_size'] = '2048000';
          $config['file_name'] = $name;
              
          $this->load->library('upload', $config);
          $this->upload->initialize($config);

          if (!is_dir('uploads')){
              //mkdir('./uploads', 0777, true);
          }


        if(isset($_FILES['image'])  ){
            //$server    = $_POST['server'];
            $errors    = array();
            $file_name = $_FILES['image']['name'];
            $file_size = $_FILES['image']['size'];
            $file_tmp  = $_FILES['image']['tmp_name'];
            $file_type = $_FILES['image']['type'];
            $error     = $_FILES['image']['error'];

            
            if(empty($errors)==true) {

             //  $modalities_name = $_POST['modalities_name'];
             //  echo "string ".$file_tmp;
             //  exit();
             //  $url = $this->config->item('url_orthanc');
             //  $curl = curl_init();

             //  curl_setopt_array($curl, array(
             //    CURLOPT_URL => "$url"."instances",
             //    CURLOPT_RETURNTRANSFER => true,
             //    CURLOPT_ENCODING => "",
             //    CURLOPT_MAXREDIRS => 10,
             //     //CURLOPT_TIMEOUT => 60, // max val : 180
             //    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
             //    CURLOPT_CUSTOMREQUEST => "POST",
             //    CURLOPT_POSTFIELDS => "$file_tmp", // lama
             //    CURLOPT_HTTPHEADER => array(
             //      "cache-control: no-cache",
             //    ),
             //  ));
             //  //print_r();

             //  $exec = curl_exec($curl);
             //  $err = curl_error($curl);

             //  curl_close($curl);

             // if ($err) {
             //    echo "cURL Error #:" . $err;
             // } else {
             //  //echo $response;
             // }

              $url = $this->config->item('url_orthanc');
              $a   = shell_exec('curl -H POST '.$url.'/instances --data-binary @'.$file_tmp);


              $json_array = json_decode($a); //Misalkan nama variabel data json Anda adalah $a lalu dekode 
                                            //dan berikan ke variabel baru $json_array
              echo "data ".$json_array;
              $data = $json_array->Status;
                 if ($data == 'AlreadyStored') {
                    # code...
                     $this->session->set_userdata("del_msg", "Sending File"); //bila data sudah ada dalam server
                     redirect('#Upload');  //ke riderect hal.masukkan file
                  }
                  elseif ($data == 'Success') {
                    # code...
                     $this->session->set_userdata("act_msg", "Sending File"); //bila data berhasil dikirim ke server
                     redirect('#Upload');  //ke riderect hal.masukkan file
                  }
                  else {
                    # code...  
                     $this->session->set_userdata("format_msg", "Sending File"); //bila data yang dikirim dengan format selain dicom/dcm
                     redirect('#Upload');  //ke riderect hal.masukkan file
                 }

            }
          }elseif (!empty($_FILES)) {
              # code...
              $tmpFile = $_FILES['file']['tmp_name'];
            //echo "string ".$tmpFile;
            // $filename = $uploadDir.'/'.time().'-'. $_FILES['file']['name'];
            // move_uploaded_file($tmpFile,$filename);
              if(empty($errors)==true) {

                $url = $this->config->item('url_orthanc');
                $a   = shell_exec('curl -H POST '.$url.'/instances --data-binary @'.$tmpFile);


                // $json_array = json_decode($a); //Misalkan nama variabel data json Anda adalah $a lalu dekode 
                //                             //dan berikan ke variabel baru $json_array
                // //echo "data ".$json_array;
                // $data = $json_array->Status;
                //    if ($data == 'AlreadyStored') {
                //       # code...
                //        $this->session->set_userdata("del_msg", "Sending File"); //bila data sudah ada dalam server
                //       redirect('#Upload');  //ke riderect hal.masukkan file
                //     }
                //     elseif ($data == 'Success') {
                //       # code...
                //        $this->session->set_userdata("act_msg", "Sending File"); //bila data berhasil dikirim ke server
                //       redirect('#Upload');  //ke riderect hal.masukkan file
                //     }
                //     else {
                //       # code...  
                //       $this->session->set_userdata("format_msg", "Sending File"); //bila data yang dikirim dengan format selain dicom/dcm
                //       redirect('#Upload');  //ke riderect hal.masukkan file
                //   }
              }
            }
            else{
              print_r($errors);
           }
      
    }  
    }
