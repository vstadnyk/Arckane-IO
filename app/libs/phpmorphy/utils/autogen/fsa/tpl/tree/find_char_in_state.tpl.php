			// tree version
			$result = true;
			$start_offset = <? echo $helper->getOffsetByTrans('$trans', '$char') ?>;
			
			// read first trans in state
			<? $helper->out($helper->storage->seek('$start_offset'), ';'); ?> 
			list(, $trans) = <? echo $helper->unpackTrans($helper->storage->read('$start_offset', $helper->getTransSize())) ?>;
			
			// If first trans is term(i.e. pointing to annot) then skip it
			if(<? echo $helper->checkTerm('$trans'); ?>) {
				// When this is single transition in state then break
				if(<? echo $helper->checkLLast('$trans'); ?> && <? echo $helper->checkRLast('$trans'); ?>) {
					$result = false;
				} else {
					$start_offset += <? echo $helper->getTransSize() ?>;
					<? $helper->out($helper->storage->seek('$start_offset'), ';'); ?> 
					list(, $trans) = <? echo $helper->unpackTrans($helper->storage->read('$start_offset', $helper->getTransSize())) ?>;
				}
			}
			
			// if all ok process rest transitions in state
			if($result) {
				// walk through state
				for($idx = 1, $j = 0; ; $j++) {
					$attr = <? echo $helper->getChar('$trans') ?>;
					
					if($attr == $char) {
						$result = true;
						break;
					} else if($attr > $char) {
						if(<? echo $helper->checkLLast('$trans') ?>) {
							$result = false;
							break;
						}
						
						$idx = $idx << 1;
					} else {
						if(<? echo $helper->checkRLast('$trans') ?>) {
							$result = false;
							break;
						}
						
						$idx = ($idx << 1) + 1;
					}
					
					if($j > 255) {
						throw new phpMorphy_Exception('Infinite recursion possible');
					}
			
					<? $offsetExp = '$start_offset + ' . $helper->idx2offset('$idx - 1') ?> 
					// read next trans
					<? $helper->out($helper->storage->seek($offsetExp), ';'); ?> 
					list(, $trans) = <? echo $helper->unpackTrans($helper->storage->read($offsetExp, $helper->getTransSize())) ?>;
				}
			}
			
