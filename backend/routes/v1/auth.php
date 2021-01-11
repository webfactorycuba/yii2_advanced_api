<?php

return [
    /**
     * @SWG\Post(
     *     path="/auth/login",
     *     summary="Login to the application",
     *     tags={"Auth"},
     *     description="Login to app for get Token access, check for `data` object",
     *     @SWG\Parameter(
     *         name="username",
     *         in="formData",
     *         type="string",
     *         description="Your Username",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         type="string",
     *         description="Your Password",
     *         required=true,
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="data user",
     *         @SWG\Schema(ref="#/definitions/LoginForm")
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="ValidateErrorException",
     *         @SWG\Schema(ref="#/definitions/ErrorValidate")
     *     )
     * )
     */
    'POST login' => 'auth/login',

    /**
     * @SWG\Post(
     *     path="/auth/password-recovery",
     *     summary="Recovery password via email",
     *     tags={"Auth"},
     *     description="Set a request for email recovery password using token",
     *     @SWG\Parameter(
     *         name="email",
     *         in="formData",
     *         type="string",
     *         description="Your email for recovery password",
     *         required=true,
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Action message",
     *         @SWG\Schema(ref="#/definitions/PasswordResetRequest")
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="ValidateErrorException",
     *         @SWG\Schema(ref="#/definitions/ErrorValidate")
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Resource not found",
     *         @SWG\Schema(ref="#/definitions/Not Found")
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="Unauthorized Validation",
     *         @SWG\Schema(ref="#/definitions/Unauthorized")
     *     )
     * )
     */
    'POST password-recovery' => 'auth/password-recovery'
];