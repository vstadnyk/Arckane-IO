array(
				'term'  => <? echo $helper->checkTerm('$rawTrans') ?> ? true : false,
				'empty' => <? echo $helper->checkEmpty('$rawTrans') ?> ? true : false,
				'attr'  => <? echo $helper->getChar('$rawTrans') ?>,
				'dest'  => <? echo $helper->getDest('$rawTrans') ?>,
			)