<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Missing View!</h2>
<p>Module: <span class="module-name"><?php echo $this->params->get('module') ?></span>, Action: <span class="module-name"><?php echo $this->params->get('action') ?></span></p>
<p>The view file corresponding to your request <span class="request"><?php echo $_SERVER['REQUEST_URI'] ?></span> was not found!</p>
<p>It should be in <span class="fs-path"><?php echo $realViewPath ?></span></p>