<?php

/*
 * Copyright (C) 2016 schurix
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

namespace LearningContextClientModuleDoctrineORM\Storage;

use LearningContextClient\Storage\StorageInterface;
use LearningContextClient\Token\AccessToken;
use LearningContextClient\Token\RefreshToken;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Persistence\ProvidesObjectManager;

/**
 * Description of DoctrineStorage
 *
 * @author schurix
 */
class DoctrineStorage implements StorageInterface, ObjectManagerAwareInterface{
	use ProvidesObjectManager;
	protected $userId;
	
	public function __construct(ObjectManager $objectManager, $userId) {
		$this->setObjectManager($objectManager);
		$this->setUserId($userId);
	}
	
	public function getUserId() {
		return $this->userId;
	}

	public function setUserId($userId) {
		$this->userId = $userId;
		return $this;
	}

	public function deleteAccessToken() {
		$token = $this->getAccessToken();
		if($token){
			$this->getObjectManager()->remove($token);
			$this->getObjectManager()->flush();
		}
	}

	public function deleteRefreshToken() {
		$token = $this->getRefreshToken();
		if($token){
			$this->getObjectManager()->remove($token);
			$this->getObjectManager()->flush();
		}
	}

	public function getAccessToken() {
		$repo = $this->getObjectManager()->getRepository(AccessToken::class);
		return $repo->findOneBy(array(
			'userId' => $this->getUserId()
		));
	}

	public function getRefreshToken() {
		$repo = $this->getObjectManager()->getRepository(RefreshToken::class);
		return $repo->findOneBy(array(
			'userId' => $this->getUserId()
		));
	}

	public function saveAccessToken(AccessToken $accessToken) {
		$this->deleteAccessToken();
		$accessToken->setUserId($this->getUserId());
		$this->getObjectManager()->persist($accessToken);
		$this->getObjectManager()->flush();
	}

	public function saveRefreshToken(RefreshToken $refreshToken) {
		$this->deleteRefreshToken();
		$refreshToken->setUserId($this->getUserId());
		$this->getObjectManager()->persist($refreshToken);
		$this->getObjectManager()->flush();
	}

}
