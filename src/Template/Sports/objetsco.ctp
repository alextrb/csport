<?php
$this->assign('title', 'Mes Objets Connectés');?>

<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('#clasTable').DataTable( {
            "order": [[ 1, "desc" ]]
        } );
    });
</script>

<div>
    <h2>Liste des objets authorisés sur votre compte :</h2>
    <table id="clasTable" class="table table-hover table-striped table-responsive" >
        <thead id="clasTableHead">
            <tr>
                <th>DEVICE ID</th>
                <th>OBJETS</th>
                <th>TRUSTED</th>
            </tr>
        </thead>
        <tbody>
            <!-- Remplissage du tableau-->
            <?php foreach ($auth_devices as $objetco){
                echo"<tr><td>".$objetco->id."</td><td>"
                              .$objetco->serial."</td><td>"
                              .(int)$objetco->trusted."</td></tr>";
            }?>
        </tbody>
    </table>
    
    <h2>Liste des objets attendant une authorisation de votre part :</h2>
    <table id="clasTable" class="table table-hover table-striped table-responsive" >
        <thead id="clasTableHead">
            <tr>
                <th>DEVICE ID</th>
                <th>OBJETS</th>
                <th>TRUSTED</th>
            </tr>
        </thead>
        <tbody>
            <!-- Remplissage du tableau-->
            <?php foreach ($waiting_devices as $objetco){
                echo"<tr><td>".$objetco->id."</td><td>"
                              .$objetco->serial."</td><td>"
                              .(int)$objetco->trusted."</td></tr>";
            }?>
        </tbody>
    </table>
 </div>