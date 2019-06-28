<?php


namespace Shopgate\ConnectSdk\Http\Persistence;

use Exception;
use kamermans\OAuth2\Persistence\TokenPersistenceInterface;
use kamermans\OAuth2\Token\TokenInterface;

class PersistenceChain implements TokenPersistenceInterface
{
    /** @var TokenPersistenceInterface[] */
    private $storages;

    /**
     * @param TokenPersistenceInterface[] $storages
     */
    public function __construct(array $storages)
    {
        $this->storages = $storages;
    }

    /**
     * Restore the token data into the give token.
     *
     * @param TokenInterface $token
     *
     * @return TokenInterface Restored token
     *
     * @throws TokenPersistenceException
     */
    public function restoreToken(TokenInterface $token)
    {
        $exceptions = [];
        foreach ($this->storages as $storage) {
            try {
                $restoredToken = $storage->restoreToken($token);
                if (!$restoredToken) {
                    continue;
                }

                return $restoredToken;
            } catch (Exception $e) {
                $exceptions[] = $e;
            }
        }

        if (!empty($exceptions) && count($exceptions) === count($this->storages)) {
            throw new TokenPersistenceException('Unable to restore token.', $exceptions);
        }

        return null;
    }

    /**
     * Save the token data.
     *
     * @param TokenInterface $token
     *
     * @throws TokenPersistenceException
     */
    public function saveToken(TokenInterface $token)
    {
        $exceptions = [];
        foreach ($this->storages as $storage) {
            try {
                return $storage->saveToken($token);
            } catch (Exception $e) {
                $exceptions[] = $e;
            }
        }

        throw new TokenPersistenceException('Unable to save token.', $exceptions);
    }

    /**
     * Delete the saved token data.
     *
     * @throws TokenPersistenceException
     */
    public function deleteToken()
    {
        $exceptions = [];
        foreach ($this->storages as $storage) {
            try {
                return $storage->deleteToken();
            } catch (Exception $e) {
                $exceptions[] = $e;
            }
        }

        throw new TokenPersistenceException('Unable to delete token.', $exceptions);
    }

    /**
     * Returns true if a token exists (although it may not be valid)
     *
     * @return bool
     *
     * @throws TokenPersistenceException
     */
    public function hasToken()
    {
        $exceptions = [];
        foreach ($this->storages as $storage) {
            try {
                return $storage->hasToken();
            } catch (Exception $e) {
                $exceptions[] = $e;
            }
        }

        throw new TokenPersistenceException('Unable to determine if a token exists.', $exceptions);
    }
}
