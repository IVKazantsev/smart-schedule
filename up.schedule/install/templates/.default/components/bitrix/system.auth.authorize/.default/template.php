<?php

/**
 * @var array $arResult
 * @var CMain $APPLICATION
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}
if (!empty($arParams["~AUTH_RESULT"]))
{
	ShowMessage($arParams["~AUTH_RESULT"]);
}

if (!empty($arResult['ERROR_MESSAGE']))
{
	ShowMessage($arResult['ERROR_MESSAGE']);
}
?>
<div class="column columns mt-6 is-flex is-flex-direction-column is-align-items-center">
	<div class="mt-3 box is-flex is-flex-direction-column is-align-content-center is-align-items-center column is-one-third">
		<p class="title bx-auth-note mt-4 is-4">
			<?= GetMessage('AUTH_TITLE') ?>
		</p>

		<form class="mt-1" name="form_auth" method="post" target="_top" action="<?= $arResult["AUTH_URL"] ?>">
			<input type="hidden" name="AUTH_FORM" value="Y"/>
			<input type="hidden" name="TYPE" value="AUTH"/>
			<?php
			if ($arResult["BACKURL"] !== ''): ?>
				<input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
			<?php
			endif ?>
			<?php
			foreach ($arResult["POST"] as $key => $value): ?>
				<input type="hidden" name="<?= $key ?>" value="<?= $value ?>"/>
			<?php
			endforeach ?>
			<div class="field">
				<label class="label"><?= GetMessage("AUTH_LOGIN") ?></label>
				<div class="control has-icons-left">
					<input class="input bx-auth-input form-control" placeholder="Введите логин" type="text" name="USER_LOGIN" maxlength="255" value="<?= $arResult["LAST_LOGIN"] ?>"/>
					<span class="icon is-small is-left">
     				<i class="fas fa-user">
						<img width="25" height="25" src="https://img.icons8.com/pastel-glyph/25/person-male--v1.png" alt="person-male--v1"/>
					</i>
    			</span>
				</div>
			</div>
			<div class="field">
				<label class="label"><?= GetMessage("AUTH_PASSWORD") ?></label>
				<div class="control has-icons-left">
					<input class="input bx-auth-input form-control" type="password" placeholder="Введите пароль" name="USER_PASSWORD" maxlength="255" autocomplete="off"/>
					<span class="icon is-small is-left">
      				<i class="fas fa-lock">
						<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="25" height="25" viewBox="0 0 128 128">
							<path d="M 51 1 C 41.07 1 33 9.07 33 19 L 33 31.800781 C 23.22 34.450781 16 43.39 16 54 L 16 104 C 16 116.68 26.32 127 39 127 L 89 127 C 101.68 127 112 116.68 112 104 L 112 54 C 112 43.39 104.78 34.450781 95 31.800781 L 95 19 C 95 9.07 86.93 1 77 1 L 51 1 z M 51 7 L 77 7 C 83.62 7 89 12.38 89 19 L 89 31 L 39 31 L 39 19 C 39 12.38 44.38 7 51 7 z M 39 37 L 89 37 C 98.37 37 106 44.63 106 54 L 106 104 C 106 113.37 98.37 121 89 121 L 39 121 C 29.63 121 22 113.37 22 104 L 22 54 C 22 44.63 29.63 37 39 37 z M 64 66 C 56.83 66 51 71.83 51 79 C 51 85.13 55.28 90.280625 61 91.640625 L 61 99 C 61 100.66 62.34 102 64 102 C 65.66 102 67 100.66 67 99 L 67 91.640625 C 72.72 90.280625 77 85.14 77 79 C 77 71.83 71.17 66 64 66 z M 64 72 C 67.86 72 71 75.14 71 79 C 71 82.86 67.86 86 64 86 C 60.14 86 57 82.86 57 79 C 57 75.14 60.14 72 64 72 z"></path>
						</svg>
					</i>
    			</span>
				</div>
				<?php
				if ($arResult["SECURE_AUTH"]): ?>
					<span class="bx-auth-secure" id="bx_auth_secure" title="<?php
					echo GetMessage("AUTH_SECURE_NOTE") ?>" style="display:none">
								<div class="bx-auth-secure-icon"></div>
							</span>
					<noscript>
							<span class="bx-auth-secure" title="<?php
							echo GetMessage("AUTH_NONSECURE_NOTE") ?>">
								<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
							</span>
					</noscript>
					<script type="text/javascript">
						document.getElementById('bx_auth_secure').style.display = 'inline-block';
					</script>
				<?php
				endif ?>
			</div>
			<?php
			if ($arResult["STORE_PASSWORD"] == "Y"): ?>
				<label class="checkbox">
					<input type="checkbox" id="USER_REMEMBER" name="USER_REMEMBER" value="Y"/><label class="" for="USER_REMEMBER">&nbsp;<?= GetMessage(
							"AUTH_REMEMBER_ME"
						) ?></label>
				</label>
			<?php
			endif ?>
			<div class="mt-2 field buttons is-grouped is-flex-direction-column is-align-items-center">
				<p class="control">
					<input type="submit" class="is-success is-dark button" name="Login"
						   value="<?= GetMessage("AUTH_AUTHORIZE") ?>"/>
				</p>
				<a class="mb-2 is-underlined has-text-black" href="/">
					<?= GetMessage('BACK_TO_SCHEDULE') ?>
				</a>
			</div>
		</form>
	</div>
</div>


<script type="text/javascript">
	<?php if ($arResult["LAST_LOGIN"] <> ''):?>
	try
	{
		document.form_auth.USER_PASSWORD.focus();
	}
	catch (e)
	{
	}
	<?php else:?>
	try
	{
		document.form_auth.USER_LOGIN.focus();
	}
	catch (e)
	{
	}
	<?php endif?>
</script>

<?php
if ($arResult["AUTH_SERVICES"]): ?>
	<?php
	$APPLICATION->IncludeComponent(
		"bitrix:socserv.auth.form", "", [
									  "AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
									  "CURRENT_SERVICE" => $arResult["CURRENT_SERVICE"],
									  "AUTH_URL" => $arResult["AUTH_URL"],
									  "POST" => $arResult["POST"],
									  "SHOW_TITLES" => $arResult["FOR_INTRANET"] ? 'N' : 'Y',
									  "FOR_SPLIT" => $arResult["FOR_INTRANET"] ? 'Y' : 'N',
									  "AUTH_LINE" => $arResult["FOR_INTRANET"] ? 'N' : 'Y',
								  ], $component, ["HIDE_ICONS" => "Y"]
	);
	?>
<?
endif ?>
