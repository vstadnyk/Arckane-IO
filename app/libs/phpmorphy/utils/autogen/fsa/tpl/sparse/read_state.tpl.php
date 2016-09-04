        $start_offset = <? echo $helper->getOffsetInFsa($helper->idx2offset('$index')) ?>;
        
        // first try read annot transition
        <? $helper->out($helper->storage->seek('$start_offset'), ';'); ?> 
        list(, $trans) = <? echo $helper->unpackTrans($helper->storage->read('$start_offset', $helper->getTransSize())) ?>;
        
        if(<? echo $helper->checkTerm('$trans') ?>) {
            $result[] = $trans;
        }
        
        // read rest
        $start_offset += <? echo $helper->getTransSize() ?>;
        foreach($this->getAlphabetNum() as $char) {
<? $offset = '$start_offset + ' . $helper->idx2offset('$char') ?>
            <? $helper->out($helper->storage->seek($offset), ';'); ?> 
            list(, $trans) = <? echo $helper->unpackTrans($helper->storage->read($offset, $helper->getTransSize())) ?>;
            
//            if(!<? echo $helper->checkEmpty('$trans') ?> && <? echo $helper->getChar('$trans') ?> == $char) {
// TODO: check term and empty flags at once i.e. $trans & 0x0300
            if(!(<? echo $helper->checkEmpty('$trans') ?> || <? echo $helper->checkTerm('$trans') ?>) && <? echo $helper->getChar('$trans') ?> == $char) {

                $result[] = $trans;
            }
        }
