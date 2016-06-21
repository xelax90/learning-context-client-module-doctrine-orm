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

namespace LearningContextClientModuleDoctrineORM\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use LearningContextClientModuleDoctrineORM\Storage\DoctrineStorage as Storage;
use LearningContextClient\Storage\ZendSessionStorage;
use Doctrine\ORM\EntityManager;
use LearningContextClient\Token\Token;

/**
 * Creates an instance of LearningContextClientModule\Storage\DoctrineStorage.
 *
 * @author schurix
 */
class DoctrineStorage implements FactoryInterface{
	public function createService(ServiceLocatorInterface $serviceLocator) {
		$auth = $serviceLocator->get('zfcuser_auth_service');
		$storage = null;
		if(!$auth->hasIdentity()){
			// If user is not logged in, use session storage
			$storage = $serviceLocator->get(ZendSessionStorage::class);
		} else {
			// if the user is logged in, create doctrine storage
			$em = $serviceLocator->get(EntityManager::class);
			$storage = new Storage($em, $auth->getIdentity()->getId());
			
			// check if the session data is newer than the doctrine data
			/* @var $sessionStorage ZendSessionStorage */
			$sessionStorage = $serviceLocator->get(ZendSessionStorage::class);
			if($this->compareTokens($sessionStorage->getAccessToken(), $storage->getAccessToken()) < 0){
				if($sessionStorage->getAccessToken()->getAccessToken()){
					$storage->saveAccessToken($sessionStorage->getAccessToken());
				}
			}
			if($this->compareTokens($sessionStorage->getRefreshToken(), $storage->getRefreshToken()) < 0){
				if($sessionStorage->getRefreshToken()->getRefreshToken()){
					$storage->saveRefreshToken($sessionStorage->getRefreshToken());
				}
			}
			$sessionStorage->deleteAccessToken();
			$sessionStorage->deleteRefreshToken();
		}
		return $storage;
	}
	
	/**
	 * Compares two tokens by their issue time. Returns -1 if $token2 is newer, 1 if $token1 is newer and 0 if both are equal.
	 * 
	 * @param Token $token1
	 * @param Token $token2
	 * @return int
	 */
	protected function compareTokens(Token $token1 = null, Token $token2 = null){
		if(!$token1 && !$token2){
			return 0;
		}
		if(!$token1){
			return 1;
		}
		if(!$token2){
			return -1;
		}
		if($token1->getIssueTime() < $token2->getIssueTime()){
			return 1;
		} elseif($token2->getIssueTime() < $token1->getIssueTime()){
			return -1;
		}
		
		return 0;
	}
}
