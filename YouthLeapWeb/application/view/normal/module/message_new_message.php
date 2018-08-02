<?php if (_utype() & UTYPE_LOGINUSER) { ?>
<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
	<i class="icon-envelope-open"></i>
	<?php if (count($mMessages) > 0) { ?>
	<span class="badge badge-default"><?php p(count($mMessages)); ?></span>
	<?php } ?>
</a>
<ul class="dropdown-menu">
    <li class="external">
    	<?php if (count($mMessages) > 0) { ?>
    		<h3><?php p(STR_NEW_MESSAGE); ?> <span class="bold"> <?php p(count($mMessages)); ?></span><?php p(STR_REACH_NUM); ?></h3>
    	<?php } else { ?>
        <h3><?php p(STR_NEW_MESSAGE); ?> <?php p(STR_NO_MESSAGE); ?></h3>
        <?php } ?>
        <a href="<?php p(_url("message/inbox")); ?>"><?php p(STR_VIEW_ALL); ?></a>
    </li>
    <?php if (count($mMessages) > 0) { ?>
    <li>
        <ul class="dropdown-menu-list scroller" style="max-width: 300px; height: 250px;" data-handle-color="#637283">
        	<?php foreach ($mMessages as $message) { ?>
        		<li>
	                <a href="<?php p(_url("message/inbox")); ?>">
		                <span class="photo">
		                <img src="<?php p(_avartar_url($message->from_id)); ?>" class="img-circle" alt="">
		                </span>
		                <span class="subject">
		                <span class="from"><?php $message->detail_decode('from_name'); ?> </span>
		                <span class="time"><?php p(_datetime_label($message->create_time)); ?> </span>
		                </span>
		                <span class="message"><?php $message->nl2br('content'); ?></span>
	                </a>
	            </li>
        	<?php } ?>
        </ul>
    </li>
    <?php } ?>
</ul>
<?php } ?>