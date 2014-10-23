<?php

/**
 * File: consumerStore.class.php
 * Created on : 2014-4-2, 0:38:56
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * Oauth用户相关数据仓库
 */
class consumerStore extends store {

	public function updateConsumer($consumer, $user_id = 0){

		if (!empty($consumer['id'])){

			// Check if the current user can update this server definition
			if (!$user_is_admin)
			{
				$osr_usa_id_ref = $this->query_one('
									SELECT osr_usa_id_ref
									FROM oauth_server_registry
									WHERE osr_id = %d
									', $consumer['id']);

				if ($osr_usa_id_ref != $user_id)
				{
					throw new OAuthException2('The user "'.$user_id.'" is not allowed to update this consumer');
				}
			}
			else
			{
				// User is an admin, allow a key owner to be changed or key to be shared
				if (array_key_exists('user_id',$consumer))
				{
					if (is_null($consumer['user_id']))
					{
						$this->query('
							UPDATE oauth_server_registry
							SET osr_usa_id_ref = NULL
							WHERE osr_id = %d
							', $consumer['id']);
					}
					else
					{
						$this->query('
							UPDATE oauth_server_registry
							SET osr_usa_id_ref = %d
							WHERE osr_id = %d
							', $consumer['user_id'], $consumer['id']);
					}
				}
			}

			$this->query('
				UPDATE oauth_server_registry
				SET osr_requester_name		= \'%s\',
					osr_requester_email		= \'%s\',
					osr_callback_uri		= \'%s\',
					osr_application_uri		= \'%s\',
					osr_application_title	= \'%s\',
					osr_application_descr	= \'%s\',
					osr_application_notes	= \'%s\',
					osr_application_type	= \'%s\',
					osr_application_commercial = IF(%d,1,0),
					osr_timestamp			= NOW()
				WHERE osr_id              = %d
				  AND osr_consumer_key    = \'%s\'
				  AND osr_consumer_secret = \'%s\'
				',
				$consumer['requester_name'],
				$consumer['requester_email'],
				isset($consumer['callback_uri']) 		? $consumer['callback_uri'] 			 : '',
				isset($consumer['application_uri']) 	? $consumer['application_uri'] 			 : '',
				isset($consumer['application_title'])	? $consumer['application_title'] 		 : '',
				isset($consumer['application_descr'])	? $consumer['application_descr'] 		 : '',
				isset($consumer['application_notes'])	? $consumer['application_notes'] 		 : '',
				isset($consumer['application_type']) 	? $consumer['application_type'] 		 : '',
				isset($consumer['application_commercial']) ? $consumer['application_commercial'] : 0,
				$consumer['id'],
				$consumer['consumer_key'],
				$consumer['consumer_secret']
				);


			$consumer_key = $consumer['consumer_key'];
		}
		else
		{
			$consumer_key	= $this->generateKey(true);
			$consumer_secret= $this->generateKey();

			// When the user is an admin, then the user can be forced to something else that the user
			if ($user_is_admin && array_key_exists('user_id',$consumer))
			{
				if (is_null($consumer['user_id']))
				{
					$owner_id = 'NULL';
				}
				else
				{
					$owner_id = intval($consumer['user_id']);
				}
			}
			else
			{
				// No admin, take the user id as the owner id.
				$owner_id = intval($user_id);
			}

			$this->query('
				INSERT INTO oauth_server_registry
				SET osr_enabled				= 1,
					osr_status				= \'active\',
					osr_usa_id_ref			= \'%s\',
					osr_consumer_key		= \'%s\',
					osr_consumer_secret		= \'%s\',
					osr_requester_name		= \'%s\',
					osr_requester_email		= \'%s\',
					osr_callback_uri		= \'%s\',
					osr_application_uri		= \'%s\',
					osr_application_title	= \'%s\',
					osr_application_descr	= \'%s\',
					osr_application_notes	= \'%s\',
					osr_application_type	= \'%s\',
					osr_application_commercial = IF(%d,1,0),
					osr_timestamp			= NOW(),
					osr_issue_date			= NOW()
				',
				$owner_id,
				$consumer_key,
				$consumer_secret,
				$consumer['requester_name'],
				$consumer['requester_email'],
				isset($consumer['callback_uri']) 		? $consumer['callback_uri'] 			 : '',
				isset($consumer['application_uri']) 	? $consumer['application_uri'] 			 : '',
				isset($consumer['application_title'])	? $consumer['application_title'] 		 : '',
				isset($consumer['application_descr'])	? $consumer['application_descr'] 		 : '',
				isset($consumer['application_notes'])	? $consumer['application_notes'] 		 : '',
				isset($consumer['application_type']) 	? $consumer['application_type'] 		 : '',
				isset($consumer['application_commercial']) ? $consumer['application_commercial'] : 0
				);
		}
		return $consumer_key;

	}
}
