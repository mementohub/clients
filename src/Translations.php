<?php

namespace iMemento\Clients;

class Translations extends AbstractClient
{
    protected $mode = 'critical';

    protected $authorization = 'service';

    public function getBaseUri()
    {
        return config('imemento-sdk.translations.base_uri');
    }

    // region CRUD

    // region Platforms
    public function listPlatforms(array $query = [])
    {
        return $this->list('platforms', $query);
    }

    public function createPlatform(array $attributes = [])
    {
        return $this->post('platforms', $attributes);
    }

    public function showPlatform(int $id)
    {
        return $this->get("platforms/$id");
    }

    public function updatePlatform(int $id, array $attributes = [])
    {
        return $this->put("platforms/$id", $attributes);
    }

    public function destroyPlatform(int $id)
    {
        return $this->delete("platforms/$id");
    }
    // endregion

    // region Languages
    public function listLanguages(array $query = [])
    {
        return $this->list('languages', $query);
    }

    public function createLanguage(array $attributes = [])
    {
        return $this->post('languages', $attributes);
    }

    public function showLanguage(int $id)
    {
        return $this->get("languages/$id");
    }

    public function updateLanguage(int $id, array $attributes = [])
    {
        return $this->put("languages/$id", $attributes);
    }

    public function destroyLanguage(int $id)
    {
        return $this->delete("languages/$id");
    }
    // endregion

    // region Identifiers
    public function listIdentifiers(array $query = [])
    {
        return $this->list('identifiers', $query);
    }

    public function createIdentifier(array $attributes = [])
    {
        return $this->post('identifiers', $attributes);
    }

    public function showIdentifier(int $id)
    {
        return $this->get("identifiers/$id");
    }

    public function updateIdentifier(int $id, array $attributes = [])
    {
        return $this->put("identifiers/$id", $attributes);
    }

    public function destroyIdentifier(int $id)
    {
        return $this->delete("identifiers/$id");
    }
    // endregion

    // region Pages
    public function listPages(array $query = [])
    {
        return $this->list('pages', $query);
    }

    public function createPage(array $attributes = [])
    {
        return $this->post('pages', $attributes);
    }

    public function showPage(int $id)
    {
        return $this->get("pages/$id");
    }

    public function updatePage(int $id, array $attributes = [])
    {
        return $this->put("pages/$id", $attributes);
    }

    public function destroyPage(int $id)
    {
        return $this->delete("pages/$id");
    }
    // endregion

    // region Operational
    public function getCoverage(int $platform_id)
    {
        return $this->get("coverage/$platform_id");
    }

    public function getUntranslatedIdentifiers(string $language_code)
    {
        return $this->get("identifiers/untranslated/$language_code");
    }

    public function getAllIdentifiers(string $app_slug, string $language_code)
    {
        return $this->get("identifiers/$app_slug/$language_code");
    }

    public function updateIdentifiers(array $identifiers)
    {
        return $this->put('identifiers/translations', $identifiers);
    }
    // endregion

    // endregion
}
