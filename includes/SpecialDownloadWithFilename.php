<?php

namespace MediaWiki\Extension\DownloadWithFilename;

use Title;
use RepoGroup;

class SpecialDownloadWithFilename extends \SpecialPage {
	private RepoGroup $repoGroup;

	public function __construct( RepoGroup $repoGroup ) {
		$this->repoGroup = $repoGroup;
		parent::__construct( 'DownloadWithFilename' );
	}

	public function execute( $subPage ) {
		$output = $this->getOutput();

		$config = $this->getConfig();
		$maxSize = $config->get( 'DWFMaxSize' );

		if ( $subPage === NULL || $subPage === '' ) {
			$this->setHeaders();
			$output->addWikiMsg( 'dwf-help-no-args', $this->getPageTitle( false )->getFullText(),
				$maxSize );

			return;
		}
		$parts = explode( "/", $subPage );
		if ( count( $parts ) > 2 ) {
			$output->showErrorPage( 'error', 'dwf-too-many', [
				$this->getPageTitle( false )->getFullText(),
				$parts[0],
				$parts[1],
			] );

			return;
		} elseif ( count( $parts ) < 2 ) {
			$ext = pathinfo( $parts[0], PATHINFO_EXTENSION );
			if ( !$ext ) {
				$ext = 'bin';
			}
			$output->showErrorPage( 'error', 'dwf-no-filename', [
				$this->getPageTitle( false )->getFullText(),
				$parts[0],
				$ext,
			] );

			return;
		}

		$origFilename = $parts[0];
		$reqFilename = $parts[1];

		$allowedCharacters = "/[^a-zA-Z0-9_\- .]+/";

		$reqFilenameS = preg_replace( $allowedCharacters, "", $reqFilename );

		if ( $reqFilenameS !== $reqFilename ) {
			$output->showErrorPage( 'error', 'dwf-bad-req-filename' );

			return;
		}

		$title = Title::newFromText( $origFilename, NS_FILE );
		if ( !$title->inNamespace( NS_FILE ) ) {
			$output->showErrorPage( 'error', 'dwf-file-not-found', [ $title->getFullText() ] );

			return;
		}
		$file = $this->repoGroup->findFile( $title );

		if ( $file === false ) {
			$output->showErrorPage( 'error', 'dwf-file-not-found', [ $title->getFullText() ] );

			return;
		}

		if ( $file->getSize() > $maxSize ) {
			$output->showErrorPage( 'error', 'dwf-file-too-large', [
				$title->getFullText(),
				$maxSize,
				$file->getSize(),
			] );

			return;
		}

		$output->disable();

		$file->getRepo()->streamFileWithStatus( $file->getPath(), [
			"Content-Disposition: attachment; filename=\"$reqFilenameS\"",
		] );
	}
}
