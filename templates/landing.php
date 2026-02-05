<?php
/**
 * SPDX-FileCopyrightText: 2026 LibreCode
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
/** @var array $_ */

$config = $_['config'];
$plans = $config['plans'];
$benefits = $config['benefits'];
?>

<div class="libresign-landing" style="--primary-color: <?php p($config['primaryColor']); ?>; --accent-color: <?php p($config['accentColor']); ?>; --bg-top: <?php p($config['backgroundTop']); ?>; --bg-bottom: <?php p($config['backgroundBottom']); ?>;">
	<header class="landing-header">
		<div class="landing-logo">
			<?php if ($_['logoUrl'] !== ''): ?>
				<img src="<?php p($_['logoUrl']); ?>" alt="<?php p($config['companyName']); ?>" />
			<?php else: ?>
				<span class="logo-mark"></span>
				<span class="logo-text"><?php p($config['companyName']); ?></span>
			<?php endif; ?>
		</div>
		<nav class="landing-actions">
			<a class="ghost-button" href="<?php p($_['loginUrl']); ?>"><?php p($config['ctaSecondary']); ?></a>
			<a class="primary-button" data-plan="professional" href="#planos"><?php p($config['ctaPrimary']); ?></a>
		</nav>
	</header>

	<section class="hero" data-reveal>
		<div class="hero-copy">
			<h1><?php p($config['heroTitle']); ?></h1>
			<p><?php p($config['heroSubtitle']); ?></p>
			<div class="hero-cta">
				<a class="primary-button" data-plan="professional" href="#planos"><?php p($config['ctaPrimary']); ?></a>
				<a class="ghost-button" href="<?php p($_['loginUrl']); ?>"><?php p($config['ctaSecondary']); ?></a>
			</div>
			<ul class="hero-bullets">
				<li>Fluxos guiados e assinatura em segundos</li>
				<li>Validade juridica com trilha de auditoria</li>
				<li>API pronta para integracoes SaaS</li>
			</ul>
		</div>
		<div class="hero-panel">
			<div class="hero-card" data-reveal>
				<span class="hero-tag">LibreSign + Nextcloud</span>
				<h3>Governanca completa</h3>
				<p>Controle centralizado de assinaturas, usuarios e certificados com relatórios em tempo real.</p>
				<div class="hero-metrics">
					<div>
						<strong>99.9%</strong>
						<span>Disponibilidade</span>
					</div>
					<div>
						<strong>24/7</strong>
						<span>Monitoramento</span>
					</div>
					<div>
						<strong>LGPD</strong>
						<span>Compliance</span>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section id="planos" class="plans" data-reveal>
		<div class="section-header">
			<h2>Planos sob medida para cada etapa</h2>
			<p>Escolha o plano que combina com o volume de contratos e escale sem friccao.</p>
		</div>
		<div class="plan-grid">
			<?php foreach ($plans as $key => $plan): ?>
				<?php $highlight = !empty($plan['highlight']); ?>
				<div class="plan-card<?php if ($highlight) { print_unescaped(' featured'); } ?>" data-plan="<?php p($key); ?>">
					<?php if (!empty($plan['badge'])): ?>
						<span class="plan-badge"><?php p($plan['badge']); ?></span>
					<?php endif; ?>
					<h3><?php p($plan['name']); ?></h3>
					<div class="plan-price"><?php p($plan['price']); ?></div>
					<ul>
						<?php foreach ($plan['features'] as $feature): ?>
							<li><?php p($feature); ?></li>
						<?php endforeach; ?>
					</ul>
					<a class="plan-cta" data-plan="<?php p($key); ?>" href="#">
						<span><?php p($plan['cta']); ?></span>
						<span class="cta-spinner"></span>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="benefits" data-reveal>
		<div class="section-header">
			<h2>Por que equipes juridicas escolhem a LibreSign</h2>
			<p>Uma plataforma pensada para seguranca, escala e confianca legal.</p>
		</div>
		<div class="benefit-grid">
			<?php foreach ($benefits as $benefit): ?>
				<div class="benefit-card">
					<div class="benefit-icon">
						<svg viewBox="0 0 24 24" aria-hidden="true">
							<path d="M12 2l7 3v6c0 5-3.5 9.7-7 11-3.5-1.3-7-6-7-11V5l7-3z" />
						</svg>
					</div>
					<h3><?php p($benefit['title']); ?></h3>
					<p><?php p($benefit['description']); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</section>

	<footer class="landing-footer">
		<div>
			<strong><?php p($config['companyName']); ?></strong>
			<p><?php p($config['companyInfo']); ?></p>
			<p><?php p($config['contactEmail']); ?></p>
		</div>
		<div class="footer-links">
			<a href="<?php p($config['termsUrl']); ?>" rel="noreferrer">Termos de uso</a>
			<a href="<?php p($config['privacyUrl']); ?>" rel="noreferrer">Politica de privacidade</a>
			<a href="mailto:<?php p($config['contactEmail']); ?>">Contato</a>
			<a href="<?php p($config['docsUrl']); ?>" rel="noreferrer">Documentacao</a>
		</div>
	</footer>
</div>

<input type="hidden" id="libresign-signup" value="<?php p($_['signupUrl']); ?>">
