<?php

namespace App\Services;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Support\Facades\Hash;

class CustomUserProvider extends EloquentUserProvider
{
    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        $plain = $credentials['password'];

        // Primeiro tentamos com o Hash padrão do Laravel
        if (Hash::check($plain, $user->getAuthPassword())) {
            return true;
        }

        // Se falhar, verificamos usando o método antigo (MD5)
        if ($this->validateOldPassword($plain, $user->getAuthPassword())) {
            // Se a senha antiga foi verificada com sucesso, atualizamos para o novo formato
            $model = $this->createModel();
            $model->where($user->getAuthIdentifierName(), $user->getAuthIdentifier())
                ->update(['password' => Hash::make($plain)]);

            return true;
        }

        return false;
    }

    /**
     * Verifica a senha usando o método antigo de criptografia
     *
     * @param string $password Senha fornecida pelo usuário
     * @param string $hashedPassword Senha armazenada no banco
     * @return bool
     */
    protected function validateOldPassword($password, $hashedPassword)
    {
        // Implemente aqui a lógica do seu método antigo de verificação de senha
        // IMPORTANTE: Substitua esta implementação pelo seu algoritmo real!

        // Exemplo para MD5 (ajuste conforme seu algoritmo específico):
        // return md5($password) === $hashedPassword;

        // Exemplo para SHA1:
        // return sha1($password) === $hashedPassword;

        // Exemplo para sistemas legados que usam, por exemplo, base64+md5:
        // return base64_encode(md5($password, true)) === $hashedPassword;

        // Implemente aqui sua lógica específica
        return md5($password) === $hashedPassword;
    }
}
