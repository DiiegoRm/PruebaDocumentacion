<?php
//require_once('class.phpmailer.php');
class SgpMail {
	public $msgType;
	public $msgSubject;
	public $msgMessage;
	private $mail;

	public function __construct($type) {
		$this->mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
		$this->mail->IsSMTP(); // telling the class to use SMTP
		$this->mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
		$this->msgType = $type;
	}
	public function send($appuser,$sql=""){
		try {
			//Usuario logueado
			$this->mail->AddAddress($appuser->email, $appuser->nombre);
			//Destinatarios
			$i=1;
			if(hasVal($sql)){
				$query = db_query($sql);
				while($row = mysqli_fetch_array($query)) {
					$this->mail->AddAddress($row['email'], $row['nombre']);
					$i++;
				}
			}
			$query = db_query("SELECT u.nombre, u.email FROM notificaciones n, usuarios u WHERE n.idusuario=u.id AND n.tipo = '".$this->msgType."'");
			while($row = mysqli_fetch_array($query)) {
				$this->mail->AddCC($row['email'], $row['nombre']);
				$i++;
			}
			$this->mail->AddBCC($this->mail->From, $this->mail->FromName);
			$this->mail->Subject = $this->msgSubject;
			$this->mail->MsgHTML($this->msgMessage);
			if($i > 0 && hasVal($this->msgSubject) && hasVal($this->msgMessage)){
				$this->mail->Send();
				//echo "<div class=\"msg-ok\">Mensaje enviado a $i destinatarios!</div>";
			}
			else {
				if($i == 0){
					echo "<div class=\"msg-warn\">No hay destinatarios configurados.</div>";
				}
				else if(!hasVal($this->msgSubject)) {
					echo "<div class=\"msg-warn\">No hay subject para el mensaje.</div>";
				}
				else if(!hasVal($this->msgMessage)) {
					echo "<div class=\"msg-warn\">No hay cuerpo del mensaje definido.</div>";
				}
			}
		} catch (phpmailerException $e) {
			echo "<div class=\"msg-error\">".$e->errorMessage()."</div>";
		} catch (Exception $e) {
			echo "<div class=\"msg-bad\">".$e->getMessage()."</div>";
		}
	}
}
?>
