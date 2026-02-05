<?php
/**
 * SPDX-FileCopyrightText: 2026 LibreCode
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
/** @var array $_ */
?>

<div class="libresign-admin">
	<form class="section" method="post" action="<?php p(\OCP\Util::linkToRoute('libresign_landing.settings.save')); ?>" enctype="multipart/form-data">
		<input type="hidden" name="requesttoken" value="<?php p($_['requesttoken']); ?>">

		<h2>LibreSign Landing</h2>
		<p class="hint">Configure textos, cores e planos exibidos na landing page de assinatura digital.</p>

		<label>
			<input type="checkbox" name="enabled" value="1" <?php if ($_['enabled']) { print_unescaped('checked'); } ?>>
			Ativar landing page na rota /login
		</label>

		<div class="grid-2">
			<label>
				Titulo principal
				<input type="text" name="hero_title" value="<?php p($_['heroTitle']); ?>" />
			</label>
			<label>
				Subtitulo
				<textarea name="hero_subtitle" rows="3"><?php p($_['heroSubtitle']); ?></textarea>
			</label>
		</div>

		<div class="grid-2">
			<label>
				Texto CTA principal
				<input type="text" name="cta_primary" value="<?php p($_['ctaPrimary']); ?>" />
			</label>
			<label>
				Texto CTA secundario
				<input type="text" name="cta_secondary" value="<?php p($_['ctaSecondary']); ?>" />
			</label>
		</div>

		<div class="grid-4">
			<label>
				Cor primaria
				<input type="text" name="primary_color" value="<?php p($_['primaryColor']); ?>" />
			</label>
			<label>
				Cor de destaque
				<input type="text" name="accent_color" value="<?php p($_['accentColor']); ?>" />
			</label>
			<label>
				Fundo superior
				<input type="text" name="background_top" value="<?php p($_['backgroundTop']); ?>" />
			</label>
			<label>
				Fundo inferior
				<input type="text" name="background_bottom" value="<?php p($_['backgroundBottom']); ?>" />
			</label>
		</div>

		<div class="grid-2">
			<label>
				URL de cadastro
				<input type="text" name="signup_url" value="<?php p($_['signupUrl']); ?>" />
			</label>
			<label>
				Contato
				<input type="text" name="contact_email" value="<?php p($_['contactEmail']); ?>" />
			</label>
		</div>

		<div class="grid-2">
			<label>
				Nome da empresa
				<input type="text" name="company_name" value="<?php p($_['companyName']); ?>" />
			</label>
			<label>
				Informacoes da empresa
				<textarea name="company_info" rows="2"><?php p($_['companyInfo']); ?></textarea>
			</label>
		</div>

		<div class="grid-3">
			<label>
				URL Termos
				<input type="text" name="terms_url" value="<?php p($_['termsUrl']); ?>" />
			</label>
			<label>
				URL Privacidade
				<input type="text" name="privacy_url" value="<?php p($_['privacyUrl']); ?>" />
			</label>
			<label>
				URL Documentacao
				<input type="text" name="docs_url" value="<?php p($_['docsUrl']); ?>" />
			</label>
		</div>

		<h3>Logo</h3>
		<div class="logo-row">
			<?php if ($_['logoUrl'] !== ''): ?>
				<img class="logo-preview" src="<?php p($_['logoUrl']); ?>" alt="Logo" />
			<?php else: ?>
				<div class="logo-placeholder">Sem logo</div>
			<?php endif; ?>
			<div>
				<input type="file" name="logo" accept="image/png,image/jpeg,image/webp" />
				<label class="inline-check">
					<input type="checkbox" name="clear_logo" value="1"> Remover logo atual
				</label>
			</div>
		</div>

		<h3>Planos</h3>
		<div class="plan-settings">
			<div class="plan-block">
				<h4>Basico</h4>
				<label>Nome <input type="text" name="plan_basic_name" value="<?php p($_['plans']['basic']['name'] ?? ''); ?>"></label>
				<label>Preco <input type="text" name="plan_basic_price" value="<?php p($_['plans']['basic']['price'] ?? ''); ?>"></label>
				<label>CTA <input type="text" name="plan_basic_cta" value="<?php p($_['plans']['basic']['cta'] ?? ''); ?>"></label>
				<label>Recursos (1 por linha)
					<textarea name="plan_basic_features" rows="6"><?php p(implode("\n", $_['plans']['basic']['features'] ?? [])); ?></textarea>
				</label>
			</div>
			<div class="plan-block">
				<h4>Profissional</h4>
				<label>Nome <input type="text" name="plan_professional_name" value="<?php p($_['plans']['professional']['name'] ?? ''); ?>"></label>
				<label>Preco <input type="text" name="plan_professional_price" value="<?php p($_['plans']['professional']['price'] ?? ''); ?>"></label>
				<label>Badge <input type="text" name="plan_professional_badge" value="<?php p($_['plans']['professional']['badge'] ?? ''); ?>"></label>
				<label>CTA <input type="text" name="plan_professional_cta" value="<?php p($_['plans']['professional']['cta'] ?? ''); ?>"></label>
				<label>Recursos (1 por linha)
					<textarea name="plan_professional_features" rows="7"><?php p(implode("\n", $_['plans']['professional']['features'] ?? [])); ?></textarea>
				</label>
			</div>
			<div class="plan-block">
				<h4>Empresarial</h4>
				<label>Nome <input type="text" name="plan_enterprise_name" value="<?php p($_['plans']['enterprise']['name'] ?? ''); ?>"></label>
				<label>Preco <input type="text" name="plan_enterprise_price" value="<?php p($_['plans']['enterprise']['price'] ?? ''); ?>"></label>
				<label>CTA <input type="text" name="plan_enterprise_cta" value="<?php p($_['plans']['enterprise']['cta'] ?? ''); ?>"></label>
				<label>Recursos (1 por linha)
					<textarea name="plan_enterprise_features" rows="7"><?php p(implode("\n", $_['plans']['enterprise']['features'] ?? [])); ?></textarea>
				</label>
			</div>
		</div>

		<h3>Beneficios</h3>
		<div class="benefit-settings">
			<?php for ($i = 0; $i < 4; $i++): ?>
				<?php $benefit = $_['benefits'][$i] ?? ['title' => '', 'description' => '']; ?>
				<div class="benefit-block">
					<label>Titulo <input type="text" name="benefit_title_<?php p((string)($i + 1)); ?>" value="<?php p($benefit['title']); ?>"></label>
					<label>Descricao
						<textarea name="benefit_description_<?php p((string)($i + 1)); ?>" rows="3"><?php p($benefit['description']); ?></textarea>
					</label>
				</div>
			<?php endfor; ?>
		</div>

		<div class="actions">
			<button class="primary" type="submit">Salvar configuracoes</button>
		</div>
	</form>
</div>
