<?php declare(strict_types=1);

namespace custombox\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use custombox\exceptions\InscriptionException;

class User extends Model {

	protected $table = "user";
	protected $primaryKey = "id_user";
	public $timestamps = false;

	public function role() {
		return $this->belongsTo(Role::class, 'roleid');
	}

	/**
	 * @throws InscriptionException
	 */
	public function inscrireUser($nom, $password, $roleId, $email) {
		$this->username = filter_var($nom, FILTER_SANITIZE_STRING);
		$password = filter_var($password, FILTER_SANITIZE_STRING);
		$this->password = password_hash($password, PASSWORD_DEFAULT);
		$this->email = filter_var($email, FILTER_SANITIZE_STRING);
		$this->roleid = $roleId;
		try {
			$this->save();
		} catch (QueryException $e) {
			echo $e->getMessage();
			if ($e->getCode() == 23000) throw new InscriptionException("Username déjà utilisé");
		}

	}

	public function modifyUser($nom, $prenom, $email, $password){
		if($this->nom != $nom){
			$this->nom = $nom;
		}
		if($this->prenom != $prenom){
			$this->prenom = $prenom;
		}
		if($this->email != $email){
			$this->email = $email;
		}
		if(isset($password)){
			$this->password = password_hash($password, PASSWORD_DEFAULT);
		}
		$this->save();
	}
}