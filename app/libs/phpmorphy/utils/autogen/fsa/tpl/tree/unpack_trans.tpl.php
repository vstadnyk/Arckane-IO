array(
				'term'  => <? echo $helper->checkTerm('$rawTrans') ?> ? true : false,
				'llast' => <? echo $helper->checkLLast('$rawTrans') ?> ? true : false,
				'rlast' => <? echo $helper->checkRLast('$rawTrans') ?> ? true : false,
				'attr'  => <? echo $helper->getChar('$rawTrans') ?>,
				'dest'  => <? echo $helper->getDest('$rawTrans') ?>,
			)