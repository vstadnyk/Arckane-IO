		$offset = <? echo $helper->getOffsetInFsa($helper->idx2offset('$index')) ?>;
		
		// read first trans
		<? $helper->out($helper->storage->seek('$offset'), ';'); ?> 
		list(, $trans) = <? echo $helper->unpackTrans($helper->storage->read('$offset', $helper->getTransSize())) ?>;
		
		// check if first trans is pointer to annot, and not single in state
		if(<? echo $helper->checkTerm('$trans') ?> && !(<? echo $helper->checkLLast('$trans') ?> || <? echo $helper->checkRLast('$trans') ?>)) {
			$result[] = $trans;
			
			list(, $trans) = <? echo $helper->unpackTrans($helper->storage->read('$offset', $helper->getTransSize())) ?>;
			$offset += <? echo $helper->getTransSize(); ?>;
		}
		
		// read rest
		for($expect = 1; $expect; $expect--) {
			if(!<? echo $helper->checkLLast('$trans') ?>) $expect++;
			if(!<? echo $helper->checkRLast('$trans') ?>) $expect++;
			
			$result[] = $trans;
			
			if($expect > 1) {
				list(, $trans) = <? echo $helper->unpackTrans($helper->storage->read('$offset', $helper->getTransSize())) ?>;
				$offset += <? echo $helper->getTransSize(); ?>;
			}
		}
