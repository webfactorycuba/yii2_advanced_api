<?php

return [
    /**
     * @SWG\Get(
     *     path="/user/profile",
     *     summary="Authenticated user profile",
     *     tags={"User"},
     *     description="Get the current user data, check for `data` object",
     *     @SWG\Response(
     *         response=200,
     *         description="data user",
     *         @SWG\Schema(ref="#/definitions/Profile")
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="Unauthorized",
     *         @SWG\Schema(ref="#/definitions/Unauthorized")
     *     )
     * )
     */
    'GET profile' => 'user/profile',
];