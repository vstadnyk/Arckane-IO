			// sparse version
			$result = true;
			<? $helper->out($helper->seekTrans('$trans', '$char'), ';'); ?> 
			list(, $trans) = <? echo $helper->readTrans('$trans', '$char') ?>;
			
			if(<? echo $helper->checkEmpty('$trans') ?> || <? echo $helper->getChar('$trans') ?> != $char) {
				$result = false;
			}
