<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 LibreCode
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace OCA\LibresignLanding\Service;

class ConfigDefaults {
	public static function defaults(): array {
		return [
			'heroTitle' => 'Assine documentos digitalmente com validade juridica',
			'heroSubtitle' => 'Automatize fluxos de assinatura, valide identidades e mantenha tudo em um unico lugar.',
			'ctaPrimary' => 'Experimente Gratis',
			'ctaSecondary' => 'Login',
			'primaryColor' => '#0f766e',
			'accentColor' => '#1d4ed8',
			'backgroundTop' => '#ecfeff',
			'backgroundBottom' => '#f8fafc',
			'companyName' => 'LibreCode',
			'companyInfo' => 'Assinatura digital SaaS para equipes juridicas e operacionais.',
			'contactEmail' => 'contato@libresign.com',
			'termsUrl' => '/terms',
			'privacyUrl' => '/privacy',
			'docsUrl' => '/docs',
			'signupUrl' => '/index.php/apps/registration/',
			'plans' => [
				'basic' => [
					'name' => 'Basico',
					'price' => 'R$ 29/mes',
					'badge' => '',
					'highlight' => false,
					'cta' => 'Comecar no Basico',
					'features' => [
						'50 documentos por mes',
						'2 assinaturas por documento',
						'Armazenamento 5 GB',
						'Suporte por email em 48h',
						'Certificado digital basico',
					],
				],
				'professional' => [
					'name' => 'Profissional',
					'price' => 'R$ 79/mes',
					'badge' => 'Mais Popular',
					'highlight' => true,
					'cta' => 'Escolher Profissional',
					'features' => [
						'Documentos ilimitados',
						'5 assinaturas por documento',
						'Armazenamento 50 GB',
						'Suporte prioritario em 24h',
						'Certificado digital avancado',
						'Historico completo de assinaturas',
						'API de integracao',
					],
				],
				'enterprise' => [
					'name' => 'Empresarial',
					'price' => 'Personalizado',
					'badge' => '',
					'highlight' => false,
					'cta' => 'Falar com Vendas',
					'features' => [
						'Documentos ilimitados',
						'Assinaturas ilimitadas',
						'Armazenamento personalizado',
						'Suporte 24/7 com gerente dedicado',
						'Certificados personalizados',
						'Integracao completa via API',
						'Whitelabel opcional',
						'SLA garantido',
					],
				],
			],
			'benefits' => [
				[
					'title' => 'Seguranca de ponta a ponta',
					'description' => 'Criptografia, trilha de auditoria e controle de acesso completo.',
				],
				[
					'title' => 'Validade juridica',
					'description' => 'Assinaturas eletronicamente validas e suporte a certificados ICP-Brasil.',
				],
				[
					'title' => 'Facil de usar',
					'description' => 'Fluxos guiados, modelos reutilizaveis e painel unificado.',
				],
				[
					'title' => 'Conformidade LGPD',
					'description' => 'Privacidade e governanca com relatorios e permissao granular.',
				],
			],
		];
	}
}
