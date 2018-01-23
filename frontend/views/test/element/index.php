<?php
?>
<div id="app">
	<el-button @click="visible=true">按钮</el-button>
	<el-dialog :visible.sync="visible" title="Hello world">
		<p>欢迎使用element</p>
	</el-dialog>
</div>