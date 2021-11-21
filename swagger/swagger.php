<?php


    /** 
     * ===================================================
     *      ADS DOCUMENTATION 
     * =================================================== 
    */ 

/**
 * @OA\Get(path="/ads/index",
 *   tags={"ads"},
 *   summary="Get ads index",
 *   description="",
 *   operationId="getAdsIndex",
 *   @OA\Parameter(
 *     name="keyword",
 *     in="query",
 *     description="Search Ads Using Keyword ",
 *     required=false,
 *     @OA\Schema(
 *         type="string"
 *     )
 *   ),
 *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Ads")),
 *   @OA\Response(response=400, description="Invalid keyword supplied"),
 *   @OA\Response(response=404, description="Ads not found")
 * )
 */

 /**
 * @OA\Get(path="/ads/list",
 *   tags={"ads"},
 *   summary="Get ads list",
 *   description="",
 *   operationId="getAdsList",
 *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Ads")),
 *   @OA\Response(response=400, description="Invalid keyword supplied"),
 *   @OA\Response(response=404, description="Ads not found")
 * )
 */

  /**
 * @OA\Get(path="/ads/list-random",
 *   tags={"ads"},
 *   summary="Get ads list random",
 *   description="",
 *   operationId="getAdsListRandom",
 *   @OA\Parameter(
 *     name="access-token",
 *     in="query",
 *     description="The access token of user ",
 *     required=true,
 *     @OA\Schema(
 *         type="string"
 *     )
 *   ),
 *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Ads")),
 *   @OA\Response(response=400, description="Invalid access-token supplied"),
 *   @OA\Response(response=404, description="Ads not found")
 * )
 */

   /**
 * @OA\Get(path="/ads/remove-attachments",
 *   tags={"ads"},
 *   summary="Remove Attachments",
 *   description="",
 *   operationId="AdsRemoveAttachments",
 *   @OA\Parameter(
 *     name="access-token",
 *     in="query",
 *     description="The access token of user ",
 *     required=true,
 *     @OA\Schema(
 *         type="string"
 *     )
 *   ),
 *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Ads")),
 *   @OA\Response(response=400, description="Invalid access-token supplied"),
 *   @OA\Response(response=404, description="Ads not found")
 * )
 */

 /**
    * @OA\Post(
    *     path="/ads/remove-ads/{id}",
    *     tags={"ads"},
    *     summary="Remove Ads ",
    *     operationId="removeAds",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
    *     @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The ads ID",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),  
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="id",
    *                     type="integer"
    *                 ),
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/ads/create",
    *     tags={"ads"},
    *     summary="Create Ads ",
    *     operationId="createAds",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="name",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="description",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="link",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="file",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="is_bottom",
    *                     type="string"
    *                 ),
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

/**
    * @OA\Post(
    *     path="/ads/update",
    *     tags={"ads"},
    *     summary="Update Ads ",
    *     operationId="updateAds",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="name",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="description",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="link",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="file",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="is_bottom",
    *                     type="string"
    *                 ),
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */


 /**
    * @OA\Post(
    *     path="/ads/delete",
    *     tags={"ads"},
    *     summary="Delete ads.",
    *     operationId="deleteAds",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of ads ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="id",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */


    /**
    * @OA\Post(
    *     path="/ads/hard-delete",
    *     tags={"ads"},
    *     summary="Delete ads.",
    *     operationId="hardDeleteAds",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of ads ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="id",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */


 /**
 * @OA\Get(path="/banner/list",
 *   tags={"banner"},
 *   summary="Get Banner list",
 *   description="",
 *   operationId="getBannerList",
 *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Banner")),
 *   @OA\Response(response=400, description="Invalid access-token supplied"),
 *   @OA\Response(response=404, description="Banner not found")
 * )
 */


 /**
 * @OA\Get(path="/event/ongoing",
 *   tags={"event"},
 *   summary="Get Ongoing Event",
 *   description="",
 *   operationId="getEventOngoing",
 *   @OA\Parameter(
 *     name="access-token",
 *     in="query",
 *     description="The access token of user ",
 *     required=true,
 *     @OA\Schema(
 *         type="string"
 *     )
 *   ),
 *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Event")),
 *   @OA\Response(response=400, description="Invalid access-token supplied"),
 *   @OA\Response(response=404, description="Event not found")
 * )
 */

  /**
 * @OA\Get(path="/event/past",
 *   tags={"event"},
 *   summary="Get Past Event",
 *   description="",
 *   operationId="getPastEvent",
 *   @OA\Parameter(
 *     name="access-token",
 *     in="query",
 *     description="The access token of user ",
 *     required=true,
 *     @OA\Schema(
 *         type="string"
 *     )
 *   ),
 *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Event")),
 *   @OA\Response(response=400, description="Invalid access-token supplied"),
 *   @OA\Response(response=404, description="Event not found")
 * )
 */

 


/**
    * @OA\Post(
    *     path="/event/join",
    *     tags={"event"},
    *     summary="Join Event",
    *     operationId="joinEvent",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="event_id",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="num_guest_brought",
    *                     type="integer",
    *                 ),
    *                 @OA\Property(
    *                     property="paid",
    *                     type="integer"
    *                 ),  
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /** 
     * ===================================================
     *      ITEM DOCUMENTATION 
     * =================================================== 
    */ 

    /**
    * @OA\Post(
    *     path="/item/add",
    *     tags={"item"},
    *     summary="Add Item",
    *     operationId="addItem",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="title",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="content",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="limit",
    *                     type="integer"
    *                 ),  
    *                 @OA\Property(
    *                     property="amount",
    *                     type="integer"
    *                 ),
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

/**
    * @OA\Post(
    *     path="/item/edit",
    *     tags={"item"},
    *     summary="Edit Item",
    *     operationId="editItem",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="item_id",
    *                     type="integer"
    *                 ),
   *                 @OA\Property(
    *                     property="title",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="content",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="limit",
    *                     type="integer"
    *                 ),  
    *                 @OA\Property(
    *                     property="amount",
    *                     type="integer"
    *                 ),
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/item/redeem",
    *     tags={"item"},
    *     summary="Redeem Item",
    *     operationId="redeemItem",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="item_id",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="pin",
    *                     type="string",
    *                 ),
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

/**
    * @OA\Post(
    *     path="/item/delete",
    *     tags={"item"},
    *     summary="Delete Item.",
    *     operationId="deleteItem",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of Item ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="id",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */


    /**
     * @OA\Get(
     *   path="/item/info",
     *   tags={"item"},
     *   summary="Get Item Info",
     *   description="",
     *   operationId="getItemInfo",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="item_id",
     *     in="query",
     *     description="The Item Id ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Item")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Item not found")
     * )
     */

    /**
     * @OA\Get(
     *   path="/item/list",
     *   tags={"item"},
     *   summary="Get Item list",
     *   description="",
     *   operationId="getItemList",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The Page Count ",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Item")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Item not found")
     * )
     */

     /**
     * @OA\Get(
     *   path="/item/redeem-list",
     *   tags={"item"},
     *   summary="Get Item Redeem list",
     *   description="",
     *   operationId="getItemRedeemList",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The Page Count ",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Item")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Item not found")
     * )
     */

     /**
     * @OA\Get(
     *   path="/item/list-all",
     *   tags={"item"},
     *   summary="Get Item list All",
     *   description="",
     *   operationId="getItemListAll",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The Page Number",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="keyword",
     *     in="query",
     *     description="The Search Title Keyword",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="status",
     *     in="query",
     *     description="The Item List Status  Type",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     in="query",
     *     description="The Item List by User ID",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Item")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Item not found")
     * )
     */


    /** 
     * ===================================================
     *      LISTENING DOCUMENTATION 
     * =================================================== 
    */ 


 /**
    * @OA\Post(
    *     path="/listing/add",
    *     tags={"listing"},
    *     summary="Add Listing",
    *     operationId="addListing",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="title",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="content",
    *                     type="string",
    *                 ),
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

/**
    * @OA\Post(
    *     path="/listing/edit",
    *     tags={"listing"},
    *     summary="Edit Listing",
    *     operationId="editListing",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="listing_id",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="title",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="content",
    *                     type="string",
    *                 ),
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/listing/delete",
    *     tags={"listing"},
    *     summary="Delete Listing.",
    *     operationId="deleteListing",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of Listing ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="listing_id",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/listing/replace-image",
    *     tags={"listing"},
    *     summary="Listin Replace Image.",
    *     operationId="replace-image",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of Listing ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="id",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="imageFile",
    *                     type="file"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */


    /**
     * @OA\Get(
     *   path="/Listing/info",
     *   tags={"listing"},
     *   summary="Get Listing Info",
     *   description="",
     *   operationId="getListingInfo",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="listing_id",
     *     in="query",
     *     description="The Listing ID",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Listing")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Listing not found")
     * )
     */

     
    /**
     * @OA\Get(
     *   path="/Listing/view-by-id",
     *   tags={"listing"},
     *   summary="Get Listing View by ID",
     *   description="",
     *   operationId="getListingById",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="listing_id",
     *     in="query",
     *     description="The Listing ID",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Listing")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Listing not found")
     * )
     */

    /**
     * @OA\Get(
     *   path="/listing/list",
     *   tags={"listing"},
     *   summary="Get Listing list",
     *   description="",
     *   operationId="getListingList",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The Page Number",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Listing")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Listing not found")
     * )
     */


    /**
     * @OA\Get(
     *   path="/listing/list-all",
     *   tags={"listing"},
     *   summary="Get Listing list All",
     *   description="",
     *   operationId="getListingListAll",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
      *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The Page Number",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="keyword",
     *     in="query",
     *     description="The Search Title Keyword",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="status",
     *     in="query",
     *     description="The List Status  Type",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     in="query",
     *     description="The List by User ID",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Listing")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Listing not found")
     * )
     */

         /**
     * @OA\Get(
     *   path="/listing/featured",
     *   tags={"listing"},
     *   summary="Get Listing Featured",
     *   description="",
     *   operationId="getListingFeatured",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="account_id",
     *     in="query",
     *     description="The Account ID",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Listing")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Listing not found")
     * )
     */


    /**
     * @OA\Get(
     *   path="/listing/gallery",
     *   tags={"listing"},
     *   summary="Get Listing Gallery",
     *   description="",
     *   operationId="getListingGallery",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     in="query",
     *     description="The Listing Gallery ID",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Listing")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Listing not found")
     * )
     */

    
    /** 
     * ===================================================
     *      MEMBER DOCUMENTATION 
     * =================================================== 
    */ 

    /**
    * @OA\Post(
    *     path="/member/register",
    *     tags={"member"},
    *     summary="Member Register ",
    *     operationId="memberRegister",
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="fullname",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="email",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="password",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="password_confirm",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="mobile_no",
    *                     type="integer"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

/**
 * @OA\Post(
 *     path="/member/login",
 *     tags={"member"},
 *     summary="Logs Member into the system",
 *     operationId="loginMember",
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="email",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     property="password",
 *                     type="string"
 *                 ), 
 *                 @OA\Property(
 *                     property="uiid",
 *                     type="number"
 *                 ),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         description="successful operation",
 *      @OA\Response(response=400, description="Invalid username/password supplied")
 *     )
 * )
 */


/**
 * @OA\Post(
 *     path="/member/login-uiid",
 *     tags={"member"},
 *     summary="Logs Member into the system",
 *     operationId="memberLoginUiid",
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="email",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     property="uiid",
 *                     type="string"
 *                 ), 
 *                 @OA\Property(
 *                     property="device_type",
 *                     description="android or ios",
 *                     type="string"
 *                 ), 
 *                 @OA\Property(
 *                     property="loginFaceid",
 *                     type="string"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         description="successful operation",
 *      @OA\Response(response=400, description="Invalid username/uiid/device_type supplied")
 *     )
 * )
 */


/**
 * @OA\Post(
 *     path="/member/login-biometric",
 *     tags={"member"},
 *     summary="Logs Member into the system",
 *     operationId="memberLoginBiometric",
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="email",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     property="uiid",
 *                     type="string"
 *                 ), 
 *                 @OA\Property(
 *                     property="device_type",
 *                     description="android or ios",
 *                     type="string"
 *                 ), 
 *                 @OA\Property(
 *                     property="loginFaceid",
 *                     type="string"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         description="successful operation",
 *      @OA\Response(response=400, description="Invalid username/uiid/device_type supplied")
 *     )
 * )
 */


/**
 * @OA\Post(
 *     path="/member/login-face-id",
 *     tags={"member"},
 *     summary="Logs Member into the system",
 *     operationId="memberLoginFaceId",
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="email",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     property="uiid",
 *                     type="string"
 *                 ), 
 *                 @OA\Property(
 *                     property="device_type",
 *                     description="android or ios",
 *                     type="string"
 *                 ), 
 *                 @OA\Property(
 *                     property="loginFaceid",
 *                     type="string"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         description="successful operation",
 *      @OA\Response(response=400, description="Invalid username/uiid/device_type supplied")
 *     )
 * )
 */

 /**
    * @OA\Post(
    *     path="/member/update-password",
    *     tags={"member"},
    *     summary="Password Update ",
    *     operationId="passwordUpdate",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="password_current",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="password_new",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="password_confirm",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

     
    /**
    * @OA\Post(
    *     path="/member/update-email",
    *     tags={"member"},
    *     summary="Email Update ",
    *     operationId="updateEmail",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of member ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="email",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    
    /**
    * @OA\Post(
    *     path="/member/update-mobile",
    *     tags={"member"},
    *     summary="mobile Update ",
    *     operationId="updateMobile",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of member ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="mobile_code",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="mobile",
    *                     type="number"
    *                )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

     
    /**
    * @OA\Post(
    *     path="/member/update-pin",
    *     tags={"member"},
    *     summary="Pin Update ",
    *     operationId="updatePin",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of member ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="pin",
    *                     type="number"
    *                 ),
    *                 @OA\Property(
    *                     property="pin_confirm",
    *                     type="number"
    *                )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

 
    /**
    * @OA\Post(
    *     path="/member/verify-password",
    *     tags={"member"},
    *     summary="Verify Password",
    *     operationId="verifyPassword",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of member ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="password",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */


    /**
     * @OA\Get(
     *   path="/member/info",
     *   tags={"member"},
     *   summary="Get Member Info",
     *   description="",
     *   operationId="getMemberInfo",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Member")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Member not found")
     * )
     */

    /**
     * @OA\Get(
     *   path="/member/redeem-list",
     *   tags={"member"},
     *   summary="Get Member redeem list",
     *   description="",
     *   operationId="getMemberRedeemList",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The Page Number",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="status",
     *     in="query",
     *     description="The Status Type",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Member")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Member not found")
     * )
     */

   /** 
    * @OA\Post(
    *     path="/member/update-profile",
    *     tags={"member"},
    *     summary="Profile Update ",
    *     operationId="updateProfile",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of member ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="step",
    *                     description="Step [1, 2, 3, 5]",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="nric",
    *                     description="Required on Step 1",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="postal_code",
    *                     description="Required on Step 1",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="chasis_number",
    *                     description="Required on Step 2",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="plate_no",
    *                     description="Required on Step 2",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="car_model",
    *                     description="Required on Step 2",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="registration_code",
    *                     description="Required on Step 2",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="are_you_owner",
    *                     description="Required on Step 2",
    *                     type="string"
    *                 ),    
    *                 @OA\Property(
    *                     property="contact_person",
    *                     description="Required on Step 3",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="emergency_no",
    *                     description="Required on Step 3",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="emergency_code",
    *                     description="Required on Step 3",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="relationship",
    *                     description="Required on Step 3",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="transfer_amount",
    *                     description="Required on Step 5",
    *                     type="integer"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/member/upload-doc",
    *     tags={"member"},
    *     summary="Upload Doc",
    *     operationId="upload-doc",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of member ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="field",
    *                     type="file     "
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
     * @OA\Get(
     *   path="/member/doc",
     *   tags={"member"},
     *   summary="Get Member Doc",
     *   description="",
     *   operationId="getMemberDoc",
     *   @OA\Parameter(
     *     name="f",
     *     in="query",
     *     description="File Field [img_profile, img_vendor, company_logo, club_logo, brand_guide]",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="u",
     *     in="query",
     *     description="User ID ",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Member")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Member not found")
     * )
    */

    /**
     * @OA\Get(
     *   path="/member/options",
     *   tags={"member"},
     *   summary="Get Member Optionss",
     *   description="",
     *   operationId="getMemberOptions",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Member")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Member not found")
     * )
    */

    /** 
    * @OA\Post(
    *     path="/member/update-personal-profile",
    *     tags={"member"},
    *     summary="Personal Profile Update ",
    *     operationId="updatePersonalProfile",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of member ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="fullname",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="nric",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="postal_code",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="country",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="unit_no",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="add_1",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="add_2",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="gender",
    *                     type="string"
    *                 ),    
    *                 @OA\Property(
    *                     property="birthday",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="profession",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="company",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="annual_salary",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="contact_person",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="emergency_no",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="emergency_code",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="relationship",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="about",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

     /** 
    * @OA\Post(
    *     path="/member/update-vendor-profile",
    *     tags={"member"},
    *     summary="Vendor Profile Update ",
    *     operationId="updateVendorProfile",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of member ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="company",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="telephone_code",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="telephone_no",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="company_email",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="company_country",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="company_postal_code",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="company_add_1",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="company_unit_no",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="company_add_2",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="fullname",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="nric",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="postal_code",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="country",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="unit_no",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="add_1",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="add_2",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="birthday",
    *                     type="string"
    *                 ),     
    *                 @OA\Property(
    *                     property="gender",
    *                     type="string"
    *                 ),     
    *                 @OA\Property(
    *                     property="about",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

     /** 
    * @OA\Post(
    *     path="/member/update-vehicle",
    *     tags={"member"},
    *     summary="Vehicle Update ",
    *     operationId="updateVehicle",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of member ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="chasis_number",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="plate_no",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="car_model",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="registration_code",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="insurance_date",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */


    /**
    *   @OA\Post(
    *     path="/member/forgot-password",
    *     tags={"member"},
    *     summary="Forgot Password",
    *     operationId="forgotPassword",
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="email",
    *                     type="string",
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

  /**
    *   @OA\Post(
    *     path="/member/forgot-password-confirm-code",
    *     tags={"member"},
    *     summary="Forgot Password Confirm Code",
    *     operationId="forgotPasswordConfirmCode",
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="email",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="reset_code",
    *                     type="string",
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    *   @OA\Post(
    *     path="/member/forgot-password-update",
    *     tags={"member"},
    *     summary="Forgot Password Update",
    *     operationId="forgotPasswordUpdate",
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="email",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="reset_code",
    *                     type="string",
    *                 ),    
    *                 @OA\Property(
    *                     property="password_new",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="password_confirm",
    *                     type="string",
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/member/register-vendor",
    *     tags={"member"},
    *     summary="Register Vendor ",
    *     operationId="register-vendor",
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="company",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="email",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="password",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="password_confirm",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="fullname",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="mobile_no",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="uiid",
    *                     type="integer"
    *                 ),    
    *                 @OA\Property(
    *                     property="device_type",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

     /** 
    * @OA\Post(
    *     path="/member/update-company-onboarding",
    *     tags={"member"},
    *     summary="Update Company Onboarding",
    *     operationId="updateCompanyOnboarding",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of member ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="step",
    *                     description="Step [1]",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="gender",
    *                     description="Required on Step 1",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="birthday",
    *                     description="Required on Step 1",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="nric",
    *                     description="Required on Step 1",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="company_country",
    *                     description="Required on Step 1",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="company_postal_code",
    *                     description="Required on Step 1",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="company_add_1",
    *                     description="Required on Step 1",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="company_unit_no",
    *                     description="Required on Step 1",
    *                     type="string"
    *                 ),    
    *                 @OA\Property(
    *                     property="company_add_2",
    *                     description="Required on Step 1",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="company",
    *                     description="Required on Step 1",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="eun",
    *                     description="Required on Step 1",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="about",
    *                     description="Required on Step 1",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="number_of_employees",
    *                     description="Required on Step 1",
    *                     type="integer"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /** 
    * @OA\Post(
    *     path="/member/add-director",
    *     tags={"member"},
    *     summary="Add Director",
    *     operationId="addDirector",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of member ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="fullname",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="email",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="mobile_code",
    *                     description="Example: sg",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="mobile_no",
    *                     description="Eight (8) digits",
    *                     type="integer"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /** 
    * @OA\Post(
    *     path="/member/update-director",
    *     tags={"member"},
    *     summary="Update Director",
    *     operationId="updateDirector",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of member ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="director_id",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="fullname",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="email",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="mobile_code",
    *                     description="Example: sg",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="mobile_no",
    *                     description="Eight (8) digits",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="is_director",
    *                     description="1 or 0",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="is_shareholder",
    *                     description="1 or 0",
    *                     type="integer"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/member/delete-director",
    *     tags={"member"},
    *     summary="delete director.",
    *     operationId="delete-director",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of member ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="director_id",
    *                     type="integer"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */


     /**
    * @OA\Post(
    *     path="/member/brand-synopsis",
    *     tags={"member"},
    *     summary="Member brand synopsis ",
    *     operationId="brand-synopsis",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="brand_synopsis",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="club_logo",
    *                     type="file",
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */


     /** 
    * @OA\Post(
    *     path="/member/update-company-profile",
    *     tags={"member"},
    *     summary="Update Company Profile",
    *     operationId="updateCompanyProfile",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
     *                 @OA\Property(
    *                     property="company",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="mobile_code",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="mobile",
    *                     type="number"
    *                 ),
    *                 @OA\Property(
    *                     property="company_email",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="company_country",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="company_postal_code",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="company_add_1",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="company_unit_no",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="company_add_2",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="fullname",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="nric",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="number_of_employees",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="eun",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="birthday",
    *                     type="string"
    *                 ),     
    *                 @OA\Property(
    *                     property="gender",
    *                     type="string"
    *                 ),     
    *                 @OA\Property(
    *                     property="about",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /** 
    * @OA\Post(
    *     path="/member/update-is-premium",
    *     tags={"member"},
    *     summary="Update Is Premium",
    *     operationId="update-is-premium",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="is_premium",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /** 
    * @OA\Post(
    *     path="/member/update-premium-status",
    *     tags={"member"},
    *     summary="Update Premium Status",
    *     operationId="update-premium-status",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="premium_status",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

  /** 
    * @OA\Post(
    *     path="/member/social-media-check",
    *     tags={"member"},
    *     summary="Social Media Check",
    *     operationId="social-media-check",
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="sm_token",
    *                     description="Social Media Token",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="login_type",
    *                     description="Login Type [1 = Facebook, 2 => google, 3 => Apple]",
    *                     type="string",
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
     * @OA\Get(
     *   path="/member/apple-user-check-redirect",
     *   tags={"member"},
     *   summary="Apple User Check Redirect",
     *   description="",
     *   operationId="apple-user-check-redirect",
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Member")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Member not found")
     * )
    */

    
    /**
     * @OA\Get(
     *   path="/member/update-topic",
     *   tags={"member"},
     *   summary="Update Topic",
     *   description="",
     *   operationId="update-topic",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="topic",
     *     in="query",
     *     description="The topic",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="fcm_token",
     *     in="query",
     *     description="The FCM Token",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="fcm_topics",
     *     in="query",
     *     description="The FCM Topics",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Member")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Member not found")
     * )
    */


    /**
     * @OA\Get(
     *   path="/member/check-admin",
     *   tags={"member"},
     *   summary="Check Admin",
     *   description="",
     *   operationId="check-admin",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Member")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Member not found")
     * )
    */

    /**
     * @OA\Get(
     *   path="/member/logo",
     *   tags={"member"},
     *   summary="Logo",
     *   description="",
     *   operationId="logo",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="account_id",
     *     in="query",
     *     description="The account Id",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Member")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Member not found")
     * )
    */


    /**
     * @OA\Get(
     *   path="/member/renewal-attachment",
     *   tags={"member"},
     *   summary="Renewal Attachment",
     *   description="",
     *   operationId="renewal-attachment",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="u",
     *     in="query",
     *     description="u ",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="size",
     *     in="query",
     *     description="size",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="f",
     *     in="query",
     *     description="f",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Member")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Member not found")
     * )
    */

    /**
     * @OA\Get(
     *   path="/member/renewal-log-card",
     *   tags={"member"},
     *   summary="Renewal Log Card",
     *   description="",
     *   operationId="renewal-log-card",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="u",
     *     in="query",
     *     description="u ",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),          
     *   @OA\Parameter(
     *     name="f",
     *     in="query",
     *     description="f",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Member")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Member not found")
     * )
    */

     /** 
    * @OA\Post(
    *     path="/member/renewal",
    *     tags={"member"},
    *     summary="Renewal",
    *     operationId="renewal",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="file",
    *                     description="Payment screenshot",
    *                     type="file"
    *                 ),
    *                 @OA\Property(
    *                     property="log_card",
    *                     description="Log card file",
    *                     type="file",
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

 /** 
    * @OA\Post(
    *     path="/member/sign-in-codes",
    *     tags={"member"},
    *     summary="Sign In Codes",
    *     operationId="sign-in-codes", 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="verification_code",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="email",
    *                     type="string",
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /** 
    * @OA\Post(
    *     path="/member/request-new-club",
    *     tags={"member"},
    *     summary="Request New Club",
    *     operationId="request-new-club",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="club_name",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="club_logo",
    *                     type="file",
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */


   /** 
    * @OA\Post(
    *     path="/member/club-registration",
    *     tags={"member"},
    *     summary="Reqister Club",
    *     operationId="club-registration",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="club_code",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="question_id",
    *                     type="integer",
    *                 ),
    *                 @OA\Property(
    *                     property="answers",
    *                     type="string"
    *                 ),          
    *                 @OA\Property(
    *                     property="file",
    *                     type="file"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
     * @OA\Get(
     *   path="/member/security-questions",
     *   tags={"member"},
     *   summary="Security Questions",
     *   description="",
     *   operationId="security-questions",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="account_id",
     *     in="query",
     *     description="The account ID",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Member")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Member not found")
     * )
    */

    /**
     * @OA\Get(
     *   path="/member/file-security-answers",
     *   tags={"member"},
     *   summary="File Security Answers",
     *   description="",
     *   operationId="file-security-answers",     
     *   @OA\Parameter(
     *     name="id",
     *     in="query",
     *     description="The Answer's ID ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Member")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Member not found")
     * )
    */

    /**
     * @OA\Get(
     *   path="/member/list-security-answers",
     *   tags={"member"},
     *   summary="List Security Answers",
     *   description="",
     *   operationId="list-security-answers",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Member")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Member not found")
     * )
    */








    /** 
     * ===================================================
     *      NEWS DOCUMENTATION 
     * =================================================== 
    */

    /**
     * @OA\Get(
     *   path="/news/list",
     *   tags={"news"},
     *   summary="Get News List",
     *   description="",
     *   operationId="getNewsList",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The Page Number.",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="keyword",
     *     in="query",
     *     description="The Keyword Title",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/News")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="News not found")
     * )
     */

         /**
     * @OA\Get(
     *   path="/news/trending",
     *   tags={"news"},
     *   summary="Get News Trending",
     *   description="",
     *   operationId="getNewsTrending",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The Page Number.",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="keyword",
     *     in="query",
     *     description="The Keyword Title",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/News")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="News not found")
     * )
     */

         /**
     * @OA\Get(
     *   path="/news/news",
     *   tags={"news"},
     *   summary="Get News News",
     *   description="",
     *   operationId="getNewsNews",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The Page Number.",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="keyword",
     *     in="query",
     *     description="The Keyword Title",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/News")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="News not found")
     * )
     */

    /**
     * @OA\Get(
     *   path="/news/happening",
     *   tags={"news"},
     *   summary="Get News Happening",
     *   description="",
     *   operationId="getNewsHappening",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The Page Number.",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="keyword",
     *     in="query",
     *     description="The Keyword Title",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/News")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="News not found")
     * )
     */

         /**
     * @OA\Get(
     *   path="/news/event",
     *   tags={"news"},
     *   summary="Get News Event",
     *   description="",
     *   operationId="getNewsEvent",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
    *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The Page Number.",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="keyword",
     *     in="query",
     *     description="The Keyword Title",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/News")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="News not found")
     * )
     */

    /**
     * @OA\Get(
     *   path="/news/guest",
     *   tags={"news"},
     *   summary="Get News Guest",
     *   description="",
     *   operationId="getNewsGuest",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The Page Number.",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="keyword",
     *     in="query",
     *     description="The Keyword Title",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/News")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="News not found")
     * )
     */



         /**
     * @OA\Get(
     *   path="/news/gallery",
     *   tags={"news"},
     *   summary="Get News Gallery",
     *   description="",
     *   operationId="getNewsGallery",
     *   @OA\Parameter(
     *     name="news_id",
     *     in="query",
     *     description="The News ID ",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/News")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="News not found")
     * )
     */

   /**
    * @OA\Post(
    *     path="/News/set-public",
    *     tags={"news"},
    *     summary="News Set Public",
    *     operationId="setPublicNews", 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="is_public",
    *                     type="integer"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /** 
     * ===================================================
     *      SPONSOR DOCUMENTATION 
     * =================================================== 
    */ 

    /**
     * @OA\Get(
     *   path="/sponsor/list",
     *   tags={"sponsor"},
     *   summary="Get Sponsor List",
     *   description="",
     *   operationId="getSponsorList",
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Sponsor")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Sponsor not found")
     * )
    */

    /**
    * @OA\Post(
    *     path="/support/inquire",
    *     tags={"support"},
    *     summary="Inquire",
    *     operationId="inquire",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of Support ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="message",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /** 
     * ===================================================
     *      USER PAYMENT DOCUMENTATION 
     * =================================================== 
    */ 

     /**
     * @OA\Get(
     *   path="/user-payment/list",
     *   tags={"user-payment"},
     *   summary="Get User Payment",
     *   description="",
     *   operationId="getUserPayment",
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/User-Payment")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="User Payment not found")
     * )
     */

     /**
    * @OA\Post(
    *     path="/user-payment/remove-ads",
    *     tags={"user-payment"},
    *     summary="Remove ads",
    *     operationId="removeAds",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of User Payment ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="file",
    *                     type="file"
    *                 ),    
    *                 @OA\Property(
    *                     property="description",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */




    /** 
     * ===================================================
     * ===================================================
     *      DASHBOARD DOCUMENTATION 
     * ===================================================
     * =================================================== 
    */

    /** 
     * ===================================================
     *      ADMIN ACCOUNT DOCUMENTATION 
     * =================================================== 
    */

    /**
     * @OA\Get(
     *   path="/admin/account/index",
     *   tags={"admin-account"},
     *   summary="Get Admin Account Index",
     *   description="",
     *   operationId="getAdminAccountIndex",
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Account")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Account not found")
     * )
     */

    /**
     * @OA\Get(
     *   path="/admin/account/list",
     *   tags={"admin-account"},
     *   summary="Get Admin Account List",
     *   description="",
     *   operationId="getAdminAccountList",
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Account")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Account not found")
     * )
    */

    /**
    * @OA\Post(
    *     path="/admin/account/create",
    *     tags={"admin-account"},
    *     summary="Create Account",
    *     operationId="createAccount",
    *     @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="company",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="company_full_name",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="address",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="file",
    *                     type="file"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/admin/account/delete",
    *     tags={"admin-account"},
    *     summary="Delete Admin Account.",
    *     operationId="deleteAdminAccount",
    *     @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of Account ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="account_id",
    *                     type="integer"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Delete(
    *     path="/admin/account/hard-delete",
    *     tags={"admin-account"},
    *     summary="Delete Admin Account.",
    *     operationId="deleteAdminAccount",
    *     @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of Account ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="account_id",
    *                     type="integer"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Put(
    *     path="/admin/account/update",
    *     tags={"admin-account"},
    *     summary="Update Account",
    *     operationId="updateAccount",
    *     @OA\Parameter(
    *       name="access-token",
    *       in="query",
    *       description="The access token of user ",
    *       required=true,
    *       @OA\Schema(
    *         type="string"
    *       )
    *     ),
    *     @OA\Parameter(
     *     name="account_id",
     *     in="query",
     *     description="The account Id ",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="company",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="company_full_name",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="address",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="file",
    *                     type="file"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
     * @OA\Get(path="/admin/account/view/{id}",
     *   tags={"admin-account"},
     *   summary="View Admin Account",
     *   description="",
     *   operationId="getAccountDetails",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="account_id",
     *     in="path",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Account")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Account not found")
     * )
    */

    /**
    * @OA\Post(
    *     path="/admin/account/on-off-ads",
    *     tags={"admin-account"},
    *     summary="on-off-ads  account.",
    *     operationId="on-off-ads",
    *     @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="enable_ads",
    *                     description="1 = on, 2 = off",
    *                     type="integer"
    *                 ),    
    *                 @OA\Property(
    *                     property="account_id",
    *                     type="integer"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */


    /**
    * @OA\Post(
    *     path="/admin/account/set-renewal-reminder",
    *     tags={"admin-account"},
    *     summary="Set Account Renewal Reminder.",
    *     operationId="set-renewal-reminder",
    *     @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="renewal_alert",
    *                     type="string"
    *                 ),    
    *                 @OA\Property(
    *                     property="account_id",
    *                     type="integer"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/admin/account/skip-member-approval",
    *     tags={"admin-account"},
    *     summary="Skip Member Approval.",
    *     operationId="skip-member-approval",
    *     @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="skip_approval",    
    *                     description="1 = allowed, 2 = not allowed",
    *                     type="string"
    *                 ),    
    *                 @OA\Property(
    *                     property="account_id",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/admin/account/set-club-code",
    *     tags={"admin-account"},
    *     summary="Set Club Code.",
    *     operationId="set-club-code",
    *     @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="account_id",
    *                     type="integer"
    *                 ),    
    *                 @OA\Property(
    *                     property="club_code",
    *                     type="number"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/admin/account/set-default-days-unverified",
    *     tags={"admin-account"},
    *     summary="Set Default Days Unverified.",
    *     operationId="set-default-days-unverified",
    *     @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="account_id",
    *                     type="integer"
    *                 ),    
    *                 @OA\Property(
    *                     property="days_unverified_reg",
    *                     type="number"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/admin/account/update-default-settings",
    *     tags={"admin-account"},
    *     summary="Update Default Settings.",
    *     operationId="update-default-settings",
    *     @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="account_id",
    *                     type="integer"
    *                 ),    
    *                 @OA\Property(
    *                     property="club_code",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="enable_ads",
    *                     type="string",
    *                 ),    
    *                 @OA\Property(
    *                     property="is_one_approval",
    *                     type="integer"
    *                 ),    
    *                 @OA\Property(
    *                     property="renewal_alert",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="skip_approval",
    *                     type="string",
    *                 ),    
    *                 @OA\Property(
    *                     property="days_unverified_reg",
    *                     type="string",
    *                 ),    
    *                 @OA\Property(
    *                     property="member_expiry",
    *                     type="integer"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */
 
    /**
    * @OA\Post(
    *     path="/admin/account/add-security-questions",
    *     tags={"admin-account"},
    *     summary="Add Security Questions.",
    *     operationId="add-security-questions",
    *     @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="account_id",
    *                     type="integer"
    *                 ),    
    *                 @OA\Property(
    *                     property="question",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="is_file_upload",
    *                     description="1 - true, 0 - false",
    *                     type="integer"
    *                 )
    *            )
    *        )         
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    *
    */


    /**
    * @OA\Post(
    *     path="/admin/account/edit-security-questions/{id}",
    *     tags={"admin-account"},
    *     summary="Edit Security Questions.",
    *     operationId="edit-security-questions",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),    
     *    @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="account_id",
    *                     type="integer"
    *                 ),    
    *                 @OA\Property(
    *                     property="question",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="is_file_upload",
    *                     description="1 - true, 0 - false",
    *                     type="integer"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/admin/account/delete-security-questions/{id}",
    *     tags={"admin-account"},
    *     summary="Delete Security Questions.",
    *     operationId="delete-security-questions",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ), 
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
     * @OA\Get(
     *   path="/admin/account/list-security-questions",
     *   tags={"admin-account"},
     *   summary="Get Admin Account List Security Questions",
     *   description="",
     *   operationId="list-security-questions",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The page number ",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="keyword",
     *     in="query",
     *     description="The question keyword",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="account_id",
     *     in="query",
     *     description="The account Id",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Account")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Account not found")
     * )
     */

    /**
     * @OA\Get(
     *   path="/admin/account/view-security-questions/{id}",
     *   tags={"admin-account"},
     *   summary="View Security Questions",
     *   description="",
     *   operationId="view-security-questions",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The question Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Account")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Account not found")
     * )
     */
    
     /**
     * @OA\Get(
     *   path="/admin/account/account-by-club-code",
     *   tags={"admin-account"},
     *   summary="Get Account By Club Code",
     *   description="",
     *   operationId="account-by-club-code",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="code",
     *     in="query",
     *     description="The club code",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Account")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Account not found")
     * )
     */

    /**
     * @OA\Get(
     *   path="/admin/account/questions-by-club-code",
     *   tags={"admin-account"},
     *   summary="Get Questions By Club Code",
     *   description="",
     *   operationId="questions-by-club-code",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="code",
     *     in="query",
     *     description="The club code",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Account")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Account not found")
     * )
     */ 

    /**
     * @OA\Get(
     *   path="/admin/account/list-account-membership",
     *   tags={"admin-account"},
     *   summary="Get List Account Membership.",
     *   description="",
     *   operationId="list-account-membership",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The Page number ",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="size",
     *     in="query",
     *     description="The row per page",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="club_code",
     *     in="query",
     *     description="The Club code ",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="account_id",
     *     in="query",
     *     description="The account Id",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="type",
     *     in="query",
     *     description="The type",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="keyword",
     *     in="query",
     *     description="The keyword",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="status",
     *     in="query",
     *     description="The status",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="premium_status",
     *     in="query",
     *     description="The premium Status",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="role",
     *     in="query",
     *     description="The user role ",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Account")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Account not found")
     * )
    */ 

    /**
    * @OA\Post(
    *     path="/admin/account/account-membership-approve",
    *     tags={"admin-account"},
    *     summary="Account Membership Approve.",
    *     operationId="account-membership-approve",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="membership_id",
    *                     type="integer"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */


 /**
    * @OA\Post(
    *     path="/admin/account/account-membership-reject",
    *     tags={"admin-account"},
    *     summary="Account Membership Reject.",
    *     operationId="account-membership-reject",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="membership_id",
    *                     type="integer"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /** 
     * ===================================================
     *      ADMIN ADS DOCUMENTATION 
     * =================================================== 
    */

    /**
     * @OA\Get(
     *   path="/admin/ads/index",
     *   tags={"admin-ads"},
     *   summary="Get Admin Ads Index",
     *   description="",
     *   operationId="getAdminAdsIndex",
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/ads")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="ads not found")
     * )
     */

    /**
     * @OA\Get(
     *   path="/admin/ads/list",
     *   tags={"admin-ads"},
     *   summary="Get Admin Ads List",
     *   description="",
     *   operationId="getAdminAdsList",
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Ads")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Ads not found")
     * )
     */

    
    /**
     * @OA\Get(path="/admin/ads/list-by-user-id",
     *   tags={"admin-ads"},
     *   summary="Ads List By User Id",
     *   description="",
     *   operationId="list-by-user-id",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     in="query",
     *     description="The User Id ",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The Page number",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="size",
     *     in="query",
     *     description="The row size per page",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/ads")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="ads not found")
     * )
    */ 

    /**
     * @OA\Get(
     *   path="/admin/ads/list-random",
     *   tags={"admin-ads"},
     *   summary="Get Admin Ads List Random",
     *   description="",
     *   operationId="list-random",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Ads")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Ads not found")
     * )
     */

    
    /**
    * @OA\Delete(
    *     path="/admin/ads/remove-ads",
    *     tags={"admin-ads"},
    *     summary="Remove ads.",
    *     operationId="remove-ads",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of ads ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="id",
    *                     description="The Ads Id",
    *                     type="integer"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/admin/ads/create",
    *     tags={"admin-ads"},
    *     summary="Create Ads",
    *     operationId="createAds",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="name",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="description",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="link",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="file",
    *                     type="file"
    *                 ),
    *                 @OA\Property(
    *                     property="account_id",
    *                     type="integer"
    *                 ),
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Delete(
    *     path="/admin/ads/delete",
    *     tags={"admin-ads"},
    *     summary="Delete Admin ads.",
    *     operationId="adminDeleteAds",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of ads ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="id",
    *                     description="The Ads Id",
    *                     type="integer"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Delete(
    *     path="/admin/ads/hard-delete",
    *     tags={"admin-ads"},
    *     summary="Admin Hard Delete Ads.",
    *     operationId="adminHardDeleteAds",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of ads ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="id",
    *                     description="The Ads Id",
    *                     type="integer"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Put(
    *     path="/admin/ads/update",
    *     tags={"admin-ads"},
    *     summary="Admin Update Ads",
    *     operationId="adminUpdateAds",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="id",
    *                     description="The Ads Id",
    *                     type="integer"
    *                 ),    
    *                 @OA\Property(
    *                     property="name",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="description",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="link",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="file",
    *                     type="file"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
     * @OA\Get(path="/admin/ads/view",
     *   tags={"admin-ads"},
     *   summary="Admin View Ads",
     *   description="",
     *   operationId="adminViewAds",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     in="query",
     *     description="The Ads Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/ads")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Ads not found")
     * )
    */ 

    /**
    * @OA\Post(
    *     path="/admin/ads/on-off-ads",
    *     tags={"admin-ads"},
    *     summary="Admin On-Off Ads.",
    *     operationId="on-off-ads",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="ads_id",
    *                     type="integer"
    *                 ),    
    *                 @OA\Property(
    *                     property="account_id",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="enable_ads",
    *                     description="enable (on) = 1",
    *                     type="integer"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */




    /** 
     * ===================================================
     *      ADMIN BANNER DOCUMENTATION 
     * =================================================== 
    */

    /**
     * @OA\Get(
     *   path="/admin/banner/index",
     *   tags={"admin-banner"},
     *   summary="Get Admin banner Index",
     *   description="",
     *   operationId="getAdminBannerIndex",
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The Page Number",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="keyword",
     *     in="query",
     *     description="The Title and Content Keyword",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *    @OA\Parameter(
     *     name="status",
     *     in="query",
     *     description="The Banner Status",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Banner")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Banner not found")
     * )
     */

    /**
     * @OA\Get(
     *   path="/admin/banner/list",
     *   tags={"admin-banner"},
     *   summary="Get Admin banner List",
     *   description="",
     *   operationId="getAdminBannerList",
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The Page Number",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="keyword",
     *     in="query",
     *     description="The Title and Content Keyword",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *    @OA\Parameter(
     *     name="status",
     *     in="query",
     *     description="The Banner Status",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Banner")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Banner not found")
     * )
     */

     /**
    * @OA\Post(
    *     path="/admin/banner/create",
    *     tags={"admin-banner"},
    *     summary="Create Banner",
    *     operationId="createBanner",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="account_id",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="image",
    *                     type="file",
    *                 ),
    *                 @OA\Property(
    *                     property="content",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/admin/banner/delete/{id}",
    *     tags={"admin-banner"},
    *     summary="Delete Admin Banner.",
    *     operationId="adminDeleteBanner",
    *     @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of banner ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The banner Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ), 
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/admin/banner/hard-delete/{id}",
    *     tags={"admin-banner"},
    *     summary="Admin Hard Delete Banner.",
    *     operationId="adminHardDeleteBanner",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of banner ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The banner Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ), 
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */ 
    

    /**
    * @OA\Post(
    *     path="/admin/banner/update",
    *     tags={"admin-banner"},
    *     summary="Admin Update banner",
    *     operationId="adminUpdateBanner",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="id",
    *                     type="integer"
    *                 ), 
    *                 @OA\Property(
    *                     property="title",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="image",
    *                     type="file",
    *                 ), 
    *                 @OA\Property(
    *                     property="content",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */


    /**
    * @OA\Post(
    *     path="/admin/banner/replace-image/{id}",
    *     tags={"admin-banner"},
    *     summary="Admin Banner Replace Image.",
    *     operationId="replaceImage",
    *     @OA\Parameter(
    *       name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The banner Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="image",
    *                     type="file"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */


    /**
    * @OA\Post(
    *     path="/admin/banner/on-off-banners",
    *     tags={"admin-banner"},
    *     summary="Admin On-Off Banners.",
    *     operationId="on-off-banners",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="banner_id",
    *                     type="integer"
    *                 ),    
    *                 @OA\Property(
    *                     property="account_id",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="status",
    *                     description="enabled (on) = 1",
    *                     type="integer",
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */


     /**
     * @OA\Get(path="/admin/banner/view/{id}",
     *   tags={"admin-banner"},
     *   summary="Admin View banner",
     *   description="",
     *   operationId="adminViewBanner",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The banner Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/banner")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="banner not found")
     * )
    */ 


  /** 
     * ===================================================
     *      ADMIN EVENT DOCUMENTATION 
     * =================================================== 
    */

    /**
     * @OA\Get(
     *   path="/admin/event/index",
     *   tags={"admin-event"},
     *   summary="Get Admin Event Index",
     *   description="",
     *   operationId="getAdminEventIndex",
     *   @OA\Parameter(
     *     name="size",
     *     in="query",
     *     description="The row per page",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
      *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The Page Number",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="keyword",
     *     in="query",
     *     description="The Title, Summary and Content Keyword",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *    @OA\Parameter(
     *     name="status",
     *     in="query",
     *     description="The Event Status",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
      *   @OA\Parameter(
     *     name="account_id",
     *     in="query",
     *     description="The Account Id",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="is_closed",
     *     in="query",
     *     description="Is Closed",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *    @OA\Parameter(
     *     name="is_public",
     *     in="query",
     *     description="Is Public",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *    @OA\Parameter(
     *     name="is_paid",
     *     in="query",
     *     description="Is Paid",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Event")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Event not found")
     * )
     */

    /**
     * @OA\Get(
     *   path="/admin/event/list",
     *   tags={"admin-event"},
     *   summary="Get Admin Event List",
     *   description="",
     *   operationId="getAdminEventList",
     *   @OA\Parameter(
     *     name="size",
     *     in="query",
     *     description="The row per page",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
      *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The Page Number",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="keyword",
     *     in="query",
     *     description="The Title, Summary and Content Keyword",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *    @OA\Parameter(
     *     name="status",
     *     in="query",
     *     description="The Event Status",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
      *   @OA\Parameter(
     *     name="account_id",
     *     in="query",
     *     description="The Account Id",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="is_closed",
     *     in="query",
     *     description="Is Closed",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *    @OA\Parameter(
     *     name="is_public",
     *     in="query",
     *     description="Is Public",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *    @OA\Parameter(
     *     name="is_paid",
     *     in="query",
     *     description="Is Paid",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Event")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Event not found")
     * )
    */

    /**
     * @OA\Get(
     *   path="/admin/event/attendees",
     *   tags={"admin-event"},
     *   summary="Get Admin Event Attendees",
     *   description="",
     *   operationId="getAdminEventAttendees",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="event_id",
     *     in="query",
     *     description="The event Id",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="size",
     *     in="query",
     *     description="The row per page",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The Page Number",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="keyword",
     *     in="query",
     *     description="The Title, Summary and Content Keyword",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *    @OA\Parameter(
     *     name="status",
     *     in="query",
     *     description="The Event Status",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Event")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Event not found")
     * )
    */


    /**
     * @OA\Get(path="/admin/event/media-list",
     *   tags={"admin-event"},
     *   summary="Admin event Media List",
     *   description="",
     *   operationId="media-list",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="size",
     *     in="query",
     *     description="The row per page",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The Page Number",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="keyword",
     *     in="query",
     *     description="The Title, Summary and Content Keyword",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Banner")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Banner not found")
     * )
    */ 

    /**
    * @OA\Post(
    *     path="/admin/event/gallery-upload/{id}",
    *     tags={"admin-event"},
    *     summary="Event Gallery Upload",
    *     operationId="gallery-upload",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),    
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The Event Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="event_id",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="files",
    *                     type="file",
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/admin/event/gallery-upload-by-token",
    *     tags={"admin-event"},
    *     summary="Event Gallery Upload By Token",
    *     operationId="gallery-upload-by-token",
    *     @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),    
     *   @OA\Parameter(
     *     name="token",
     *     in="query",
     *     description="The token ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="event_id",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="files",
    *                     type="file",
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/admin/event/gallery-delete/{id}",
    *     tags={"admin-event"},
    *     summary="Delete Admin event.",
    *     operationId="adminEventGalleryDelete",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of event ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The Event Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ), 
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/admin/event/delete-media",
    *     tags={"admin-event"},
    *     summary="Admin Event Delete Media.",
    *     operationId="adminEventDeleteMedia",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of event ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="id",
    *                     type="integer"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/admin/event/media-upload",
    *     tags={"admin-event"},
    *     summary="Admin Event Media Upload.",
    *     operationId="media-upload",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="title",
    *                     type="string"
    *                 ),    
    *                 @OA\Property(
    *                     property="filename",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="froala",
    *                     type="string",
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */


    /**
    * @OA\Post(
    *     path="/admin/event/create",
    *     tags={"admin-event"},
    *     summary="Create Event",
    *     operationId="createEvent",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="title",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="content",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="summary",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="order",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="is_public",
    *                     type="string"
    *                 ),    
    *                 @OA\Property(
    *                     property="place",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="event_time",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="cut_off_at",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="limit",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="is_paid",
    *                     type="string"
    *                 ),   
    *                 @OA\Property(
    *                     property="event_fee",
    *                     type="string"
    *                 ),
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/admin/event/delete/{id}",
    *     tags={"admin-event"},
    *     summary="Delete Admin event.",
    *     operationId="adminDeleteEvent",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of event ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),    
     *       @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The event ID ",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ), 
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/admin/event/hard-delete",
    *     tags={"admin-event"},
    *     summary="Admin Hard Delete event.",
    *     operationId="adminHardDeleteEvent",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of event ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),    
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The event Id ",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ), 
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */ 
    

    /**
    * @OA\Post(
    *     path="/admin/event/update/{id}",
    *     tags={"admin-event"},
    *     summary="Admin Update Event",
    *     operationId="adminUpdateEvent",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),    
     *       @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The event Id",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
   *                 @OA\Property(
    *                     property="title",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="content",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="summary",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="order",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="is_public",
    *                     type="string"
    *                 ),    
    *                 @OA\Property(
    *                     property="place",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="event_time",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="cut_off_at",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="limit",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="is_paid",
    *                     type="string"
    *                 ),   
    *                 @OA\Property(
    *                     property="event_fee",
    *                     type="string"
    *                 ),
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
     * @OA\Get(path="/admin/event/view/{id}",
     *   tags={"admin-event"},
     *   summary="Admin View event",
     *   description="",
     *   operationId="adminViewEvent",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/event")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="event not found")
     * )
    */ 

    
    /**
    * @OA\Post(
    *     path="/admin/event/confirm-attendee",
    *     tags={"admin-event"},
    *     summary="Admin event Confirm Attendee.",
    *     operationId="confirm-attendee",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="attendee_id",
    *                     type="integer"
    *                 ),    
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/admin/event/cancel-attendee",
    *     tags={"admin-event"},
    *     summary="Admin Event Cancel Attendee.",
    *     operationId="cancel-attendee",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="attendee_id",
    *                     type="integer"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */


    /**
     * @OA\Get(
     *   path="/admin/event/list-image-gallery",
     *   tags={"admin-event"},
     *   summary="Get Admin Event List Image Gallery",
     *   description="",
     *   operationId="EventListImageGallery",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The page number ",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="size",
     *     in="query",
     *     description="The rows per page",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Event")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Event not found")
     * )
    */


    /**
     * @OA\Get(
     *   path="/admin/event/view-image-gallery/{id}",
     *   tags={"admin-event"},
     *   summary="Get Admin Event View Image Gallery",
     *   description="",
     *   operationId="EventViewImageGalley",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),    
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The event gallery Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Event")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Event not found")
     * )
    */

    /**
    * @OA\Post(
    *     path="/admin/event/create-gallery/{id}",
    *     tags={"admin-event"},
    *     summary="Admin Event Create Gallery.",
    *     operationId="create-gallery",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The Event Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="image",
    *                     type="file"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */


    /**
    * @OA\Post(
    *     path="/admin/event/remove-image-gallery/{id}",
    *     tags={"admin-event"},
    *     summary="Admin Event Remove Image Gallery.",
    *     operationId="remove-image-gallery",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *       @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The Event Gallery Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ), 
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/admin/event/replace-image-gallery/{id}",
    *     tags={"admin-event"},
    *     summary="Admin Event Replace Image Gallery.",
    *     operationId="replace-image-gallery",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *       @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The Event Gallery Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),  
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="gallery_id",
    *                     type="integer"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */


    /**
    * @OA\Post(
    *     path="/admin/event/set-default-settings/{id}",
    *     tags={"admin-event"},
    *     summary="Admin Event Set Default Settings.",
    *     operationId="set-default-settings",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),    
     *       @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The Event Id",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="order",
    *                     type="string"
    *                 ),    
    *                 @OA\Property(
    *                     property="is_public",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="place",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="event_time",
    *                     type="string"
    *                 ),    
    *                 @OA\Property(
    *                     property="cut_off_at",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="limit",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="is_paid",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="event_fee",
    *                     type="string",
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */


    /** 
     * ===================================================
     *      ADMIN LISTING DOCUMENTATION 
     * =================================================== 
    */

    /**
     * @OA\Get(
     *   path="/admin/listing/list",
     *   tags={"admin-listing"},
     *   summary="Get Admin Listing List",
     *   description="",
     *   operationId="getAdminListingList",        
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/listing")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="listing not found")
     * )
     */

    /**
    * @OA\Post(
    *     path="/admin/listing/update/{id}",
    *     tags={"admin-listing"},
    *     summary="Admin Update Listing",
    *     operationId="adminUpdateListing",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),   
     *       @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The Listing ID",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),       
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="listing_id",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="title",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="content",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */
 

    
    /**
     * @OA\Get(path="/admin/listing/approve/{id}",
     *   tags={"admin-listing"},
     *   summary="Listing Approve",
     *   description="",
     *   operationId="approveListing",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/listing")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="listing not found")
     * )
    */ 

    /**
    * @OA\Delete(
    *     path="/admin/listing/delete/{id}",
    *     tags={"admin-listing"},
    *     summary="Delete listing.",
    *     operationId="delete",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of listing ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *       @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The Listing ID",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),  
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */
    

    /**
    * @OA\Post(
    *     path="/admin/listing/create",
    *     tags={"admin-listing"},
    *     summary="Create listing",
    *     operationId="createlisting",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="title",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="content",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
     * @OA\Get(path="/admin/listing/view-by-id/{id}",
     *   tags={"admin-listing"},
     *   summary="Admin View Listing By Id",
     *   description="",
     *   operationId="adminViewListingById",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="listing_id",
     *     in="path",
     *     description="The listing id ",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/listing")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="listing not found")
     * )
    */ 

   

    /**
     * @OA\Get(path="/admin/listing/info/{id}",
     *   tags={"admin-listing"},
     *   summary="Admin Listing Info",
     *   description="",
     *   operationId="adminListingInfo",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="listing_id",
     *     in="path",
     *     description="The Listing Id ",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/listing")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="listing not found")
     * )
    */ 



    /** 
     * ===================================================
     *      ADMIN MEMBER DOCUMENTATION 
     * =================================================== 
    */

    
 /**
 * @OA\Post(
 *     path="/admin/member/login",
 *     tags={"admin-member"},
 *     summary="Logs user into the system",
 *     operationId="loginAdminUser",
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="email",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     property="password",
 *                     type="string"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         description="successful operation",
 *      @OA\Response(response=400, description="Invalid username/password supplied")
 *     )
 * )
 */


    /**
     * @OA\Get(
     *   path="/admin/member/info",
     *   tags={"admin-member"},
     *   summary="Get Member Info",
     *   description="",
     *   operationId="getMemberInfo",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Member")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Member not found")
     * )
     */

      /**
     * @OA\Get(
     *   path="/admin/member/doc",
     *   tags={"admin-member"},
     *   summary="Get Member Doc",
     *   description="",
     *   operationId="getMemberDoc",
     *   @OA\Parameter(
     *     name="f",
     *     in="query",
     *     description="File Field [img_profile, img_vendor, company_logo, club_logo, brand_guide]",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="u",
     *     in="query",
     *     description="User ID ",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Member")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Member not found")
     * )
     */

    /**
     * @OA\Get(path="/admin/member/info-by-user-id",
     *   tags={"admin-member"},
     *   summary="Admin member Info",
     *   description="",
     *   operationId="adminMemberInfo",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     in="query",
     *     description="The user  Id",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/member")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="member not found")
     * )
    */ 
 

    /**
     * @OA\Get(
     *   path="/admin/member/options",
     *   tags={"admin-member"},
     *   summary="Get Admin Member Optionss",
     *   description="",
     *   operationId="getAdminMemberOptions",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Member")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Member not found")
     * )
    */    


    /**
     * @OA\Get(path="/admin/member/options-by-user-id",
     *   tags={"admin-member"},
     *   summary="Get Admin member Options By User ID",
     *   description="",
     *   operationId="getAdminMemberOptionsByUserId",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     in="query",
     *     description="The user Id ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/member")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="member not found")
     * )
    */ 
 
     /**
    * @OA\Post(
    *     path="/admin/member/upload-doc",
    *     tags={"admin-member"},
    *     summary="Admin Member Upload Doc",
    *     operationId="upload-doc",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of member ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="field",
    *                     type="file"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

        /**
    *   @OA\Post(
    *     path="/admin/member/forgot-password",
    *     tags={"admin-member"},
    *     summary="Forgot Password",
    *     operationId="forgotPassword",
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="account_id",
    *                     type="integer",
    *                 ),
    *                 @OA\Property(
    *                     property="email",
    *                     type="string",
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

  /**
    *   @OA\Post(
    *     path="/admin/member/forgot-password-confirm-code",
    *     tags={"admin-member"},
    *     summary="Forgot Password Confirm Code",
    *     operationId="forgotPasswordConfirmCode",
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="account_id",
    *                     type="integer",
    *                 ),
    *                 @OA\Property(
    *                     property="email",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="reset_code",
    *                     type="string",
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    *   @OA\Post(
    *     path="/admin/member/forgot-password-update",
    *     tags={"admin-member"},
    *     summary="Forgot Password Update",
    *     operationId="forgotPasswordUpdate",
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="account_id",
    *                     type="integer",
    *                 ),
    *                 @OA\Property(
    *                     property="email",
    *                     type="string",
    *                 ),    
    *                 @OA\Property(
    *                     property="reset_code",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="password_new",
    *                     type="string",
    *                 ),    
    *                 @OA\Property(
    *                     property="password_confirm",
    *                     type="string",
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

      /**
    *   @OA\Post(
    *     path="/admin/member/social-media-check",
    *     tags={"admin-member"},
    *     summary="Social Media check",
    *     operationId="social-media-check",
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="sm_token",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="login_type",
    *                     type="string",
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */


    /**
    * @OA\Get(
    *     path="/admin/member/index",
    *     tags={"admin-member"},
    *     summary="Admin Member Index",
    *     operationId="adminMemberIndex",
    *     @OA\Parameter(
    *       name="page",
    *       in="query",
    *       description="The Page size ",
    *       required=false,
    *       @OA\Schema(
    *         type="integer"
    *       )
    *     ),     
    *     @OA\Parameter(
    *       name="size",
    *       in="query",
    *       description="The number of rows per page ",
    *       required=false,
    *       @OA\Schema(
    *         type="integer"
    *       )
    *     ), 
    *     @OA\Parameter(
    *       name="keyword",
    *       in="query",
    *       description="The fullname, vendor_name, email, mobile keyword ",
    *       required=false,
    *       @OA\Schema(
    *         type="string"
    *       )
    *   ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */


    
    /**
    * @OA\Get(
    *     path="/admin/member/list",
    *     tags={"admin-member"},
    *     summary="Admin Member List",
    *     operationId="adminMemberList",
     *     @OA\Parameter(
    *       name="page",
    *       in="query",
    *       description="The Page size ",
    *       required=false,
    *       @OA\Schema(
    *         type="integer"
    *       )
    *     ),     
    *     @OA\Parameter(
    *       name="size",
    *       in="query",
    *       description="The number of rows per page ",
    *       required=false,
    *       @OA\Schema(
    *         type="integer"
    *       )
    *     ), 
    *     @OA\Parameter(
    *       name="keyword",
    *       in="query",
    *       description="The fullname, vendor_name, email, mobile keyword ",
    *       required=false,
    *       @OA\Schema(
    *         type="string"
    *       )
    *   ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */


    /**
    * @OA\Post(
    *     path="/admin/member/create",
    *     tags={"admin-member"},
    *     summary="Member: Create ",
    *     operationId="memberCreate",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of member ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="fullname",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="email",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="password",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="password_confirm",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="mobile",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/admin/member/create-no-approval",
    *     tags={"admin-member"},
    *     summary="Admin Create No Approval Member",
    *     operationId="create-no-approval",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of member ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
       *                 @OA\Property(
    *                     property="fullname",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="email",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="password",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="password_confirm",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="mobile",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/admin/member/update",
    *     tags={"admin-member"},
    *     summary="Admin Update Member",
    *     operationId="adminmUpdateMember",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of member ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\Parameter(
    *       name="user_id",
    *       in="query",
    *       description="The user ID ",
    *       required=true,
    *       @OA\Schema(
    *         type="string"
    *       )
    *     ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="fullname",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="email",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="mobile",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
     * @OA\Post(path="/admin/member/approve",
     *   tags={"admin-member"},
     *   summary="Admin Approve Member",
     *   description="",
     *   operationId="approve-member",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="user_id",
    *                     type="integer"
    *                 )
    *            )
    *        )
    *    ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/user-payment")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="user-payment not found")
     * )
    */ 

    /**
     * @OA\Post(path="/admin/member/reject",
     *   tags={"admin-member"},
     *   summary="Admin Member reject",
     *   description="",
     *   operationId="reject-member",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="user_id",
    *                     type="integer"
    *                 )
    *            )
    *        )
    *    ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Member")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Member not found")
     * )
    */ 
   


    /**
    * @OA\Delete(
    *     path="/admin/member/delete",
    *     tags={"admin-member"},
    *     summary="Admin Delete Member",
    *     operationId="adminDeleteMember",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of member ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),  
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="user_id",
    *                     type="string"
    *                 ),
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Put(
    *     path="/admin/member/set-sponsor",
    *     tags={"admin-member"},
    *     summary="Admin set-sponsor member",
    *     operationId="set-sponsor",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="user_id",
    *                     type="string"
    *                 ),
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Put(
    *     path="/admin/member/sponsor-level",
    *     tags={"admin-member"},
    *     summary="Admin sponsor-level member",
    *     operationId="sponsor-level",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),   
     *   @OA\Parameter(
     *     name="user_id",
     *     in="query",
     *     description="The user Id ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="level",
    *                     type="string"
    *                 ),
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Put(
    *     path="/admin/member/set-expiry",
    *     tags={"admin-member"},
    *     summary="Admin set-expiry member",
    *     operationId="set-expiry",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="member_expiry",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="account_id",
    *                     type="integer",
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */


    /**
     * @OA\Post(path="/admin/member/change-role",
     *   tags={"admin-member"},
     *   summary="Admin Member Change Role",
     *   description="",
     *   operationId="change-role-member",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="user_id",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="role",
    *                     type="integer",
    *                 )
    *            )
    *        )
    *    ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Member")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Member not found")
     * )
    */ 

    /**
     * @OA\Post(path="/admin/member/set-default-expiry",
     *   tags={"admin-member"},
     *   summary="Admin Member Set Expiry Default",
     *   description="",
     *   operationId="set-default-expiry-member",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="account_id",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="member_expiry",
    *                     description="expiry date",
    *                     type="string",
    *                 )
    *            )
    *        )
    *    ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Member")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Member not found")
     * )
    */ 

/**
     * @OA\Post(path="/admin/member/set-renewal-reminder/{id}",
     *   tags={"admin-member"},
     *   summary="Admin Member Set Renewal Reminder",
     *   description="",
     *   operationId="set-renewal-reminder",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="account_id",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="renewal_alert",
    *                     type="integer",
    *                 )
    *            )
    *        )
    *    ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Member")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Member not found")
     * )
    */ 

    /**
     * @OA\Post(path="/admin/member/skip-member-approval",
     *   tags={"admin-member"},
     *   summary="Admin Skip Member Approval",
     *   description="",
     *   operationId="skip-member-approval",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="account_id",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="skip_approval",
    *                     description="1 = allowed else not allowed",
    *                     type="integer",
    *                 )
    *            )
    *        )
    *    ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Member")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Member not found")
     * )
    */ 

     /**
     * @OA\Post(path="/admin/member/set-one-approval",
     *   tags={"admin-member"},
     *   summary="Admin Set One Approval",
     *   description="",
     *   operationId="set-one-approval",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="account_id",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="is_one_approval",
    *                     description="1 = allowed else not allowed",
    *                     type="integer",
    *                 )
    *            )
    *        )
    *    ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Member")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Member not found")
     * )
    */ 


    /**
     * @OA\Post(path="/admin/member/update-default-settings",
     *   tags={"admin-member"},
     *   summary="Admin Update Default Settings",
     *   description="",
     *   operationId="update-default-settings",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="account_id",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="user_id",
    *                     type="integer",
    *                 ),    
    *                 @OA\Property(
    *                     property="club_code",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="enable_ads",
    *                     type="integer",
    *                 ),    
    *                 @OA\Property(
    *                     property="is_one_approval",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="renewal_alert",
    *                     type="integer",
    *                 ),    
    *                 @OA\Property(
    *                     property="skip_approval",
    *                     type="integer"
    *                 )
    *            )
    *        )
    *    ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Member")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Member not found")
     * )
    */ 

     /**
    * @OA\Get(
    *     path="/admin/member/file-security-answers",
    *     tags={"admin-member"},
    *     summary="Admin Member File Security Answers",
    *     operationId="file-security-answers",
    *     @OA\Parameter(
     *     name="id",
     *     in="query",
     *     description="The answer Id",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */




    /** 
     * ===================================================
     *      ADMIN NEWS DOCUMENTATION 
     * =================================================== 
    */

    /**
     * @OA\Get(
     *   path="/admin/news/list",
     *   tags={"admin-news"},
     *   summary="Get Admin News List",
     *   description="",
     *   operationId="getAdminNewsList",
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The page number",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="size",
     *     in="query",
     *     description="The rows per page",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="keyword",
     *     in="query",
     *     description="The title keyword",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="status",
     *     in="query",
     *     description="status",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="category",
     *     in="query",
     *     description="The news category",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="type",
     *     in="query",
     *     description="The news type",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/News")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="News not found")
     * )
     */

     /**
     * @OA\Get(
     *   path="/admin/news/index",
     *   tags={"admin-news"},
     *   summary="Get Admin News Index",
     *   description="",
     *   operationId="getAdminNewsIndex",
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The page number",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="size",
     *     in="query",
     *     description="The rows per page",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="keyword",
     *     in="query",
     *     description="The title keyword",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="status",
     *     in="query",
     *     description="status",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="category",
     *     in="query",
     *     description="The news category",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="type",
     *     in="query",
     *     description="The news type",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/News")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="News not found")
     * )
     */ 

    
    /**
    * @OA\Post(
    *     path="/admin/news/create",
    *     tags={"admin-news"},
    *     summary="Create News",
    *     operationId="createNews",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="summary",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="title",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="content",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="order",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="is_news",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="is_guest",
    *                     type="string",
    *                 ),    
    *                 @OA\Property(
    *                     property="is_trending",
    *                     type="string"
    *                 ),    
    *                 @OA\Property(
    *                     property="is_event",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="is_happening",
    *                     type="string",
    *                 ),    
    *                 @OA\Property(
    *                     property="is_public",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */
 

    /**
    * @OA\Post(
    *     path="/admin/news/update",
    *     tags={"admin-news"},
    *     summary="Admin Update News",
    *     operationId="adminUpdateNews",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="news_id",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="summary",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="title",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="content",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="order",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="is_news",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="is_guest",
    *                     type="string",
    *                 ),    
    *                 @OA\Property(
    *                     property="is_trending",
    *                     type="string"
    *                 ),    
    *                 @OA\Property(
    *                     property="is_event",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="is_happening",
    *                     type="string",
    *                 ),    
    *                 @OA\Property(
    *                     property="is_public",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */
 
    /**
    * @OA\Delete(
    *     path="/admin/news/delete/{id}",
    *     tags={"admin-news"},
    *     summary="Delete News.",
    *     operationId="delete-news",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of news ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The news Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),  
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */
    

    /**
    * @OA\Delete(
    *     path="/admin/news/hard-delete/{id}",
    *     tags={"admin-news"},
    *     summary="Hard Delete News.",
    *     operationId="hard-delete",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of news ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The news Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),  
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/admin/news/set-default-settings/{id}",
    *     tags={"admin-news"},
    *     summary="Admin news Set Default Settings.",
    *     operationId="set-default-settings",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The news Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="order",
    *                     type="string"
    *                 ),    
    *                 @OA\Property(
    *                     property="is_news",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="is_guest",
    *                     type="string",
    *                 ),    
    *                 @OA\Property(
    *                     property="is_trending",
    *                     type="string"
    *                 ),    
    *                 @OA\Property(
    *                     property="is_event",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="is_happening",
    *                     type="string",
    *                 ),    
    *                 @OA\Property(
    *                     property="is_public",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
     * @OA\Get(path="/admin/news/view/{id}",
     *   tags={"admin-news"},
     *   summary="Admin View news",
     *   description="",
     *   operationId="adminViewNews",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The news Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/news")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="news not found")
     * )
    */ 


    /**
     * @OA\Get(
     *   path="/admin/news/list-image-gallery",
     *   tags={"admin-news"},
     *   summary="Get Admin news List Image Gallery",
     *   description="",
     *   operationId="newsListImageGallery",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The page",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="size",
     *     in="query",
     *     description="The rows per page",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/news")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="news not found")
     * )
    */



    /**
    * @OA\Post(
    *     path="/admin/news/create-gallery/{id}",
    *     tags={"admin-news"},
    *     summary="Admin news Create Gallery.",
    *     operationId="create-gallery",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *       @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The news Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="news_id",
    *                     type="integer"
    *                 ),    
    *                 @OA\Property(
    *                     property="image",
    *                     type="file"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */


    /**
    * @OA\Post(
    *     path="/admin/news/remove-image-gallery/{id}",
    *     tags={"admin-news"},
    *     summary="Admin news Remove Image Gallery.",
    *     operationId="remove-image-gallery",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *       @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The news Gallery Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ), 
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/admin/news/replace-image-gallery/{id}",
    *     tags={"admin-news"},
    *     summary="Admin news Replace Image Gallery.",
    *     operationId="replace-image-gallery",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *       @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The news Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="gallery_id",
    *                     type="integer"
    *                 ),    
    *                 @OA\Property(
    *                     property="files",
    *                     type="file"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
    * @OA\Post(
    *     path="/admin/news/add-gallery/{id}",
    *     tags={"admin-news"},
    *     summary="Admin News Add Gallery",
    *     operationId="addGalleryNews",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),    
     *       @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The news Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="news_id",
    *                     type="integer"
    *                 ),
    *                 @OA\Property(
    *                     property="image",
    *                     type="file",
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */


    /** 
     * ===================================================
     *      ADMIN SPONSOR DOCUMENTATION 
     * =================================================== 
    */

    /**
     * @OA\Get(
     *   path="/admin/sponsor/list",
     *   tags={"admin-sponsor"},
     *   summary="Get Admin Sponsor List",
     *   description="",
     *   operationId="getAdminSponsorList",
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The page number",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="size",
     *     in="query",
     *     description="The rows per page",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="keyword",
     *     in="query",
     *     description="The title keyword",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="status",
     *     in="query",
     *     description="status",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="account_id",
     *     in="query",
     *     description="The account Id",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="type",
     *     in="query",
     *     description="The sponsor type",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="sponsor_level",
     *     in="query",
     *     description="The Sponsor level",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="premium_status",
     *     in="query",
     *     description="The premium status",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="role",
     *     in="query",
     *     description="The Sponsor role",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Sponsor")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Sponsor not found")
     * )
     */

     /**
     * @OA\Get(
     *   path="/admin/sponsor/index",
     *   tags={"admin-sponsor"},
     *   summary="Get Admin Sponsor Index",
     *   description="",
     *   operationId="getAdminSponsorIndex",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The page number",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="size",
     *     in="query",
     *     description="The rows per page",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="keyword",
     *     in="query",
     *     description="The title keyword",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="status",
     *     in="query",
     *     description="status",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="account_id",
     *     in="query",
     *     description="The account Id",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="type",
     *     in="query",
     *     description="The sponsor type",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="sponsor_level",
     *     in="query",
     *     description="The Sponsor level",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="premium_status",
     *     in="query",
     *     description="The premium status",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="role",
     *     in="query",
     *     description="The Sponsor role",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Sponsor")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Sponsor not found")
     * )
     */ 
 

    /**
    * @OA\Post(
    *     path="/admin/sponsor/update/{id}",
    *     tags={"admin-sponsor"},
    *     summary="Admin Update Sponsor",
    *     operationId="adminUpdateSponsor",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The sponsor Id ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
     *                 @OA\Property(
    *                     property="email",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="role",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="mobile",
    *                     type="number"
    *                 ),  
    *                 @OA\Property(
    *                     property="vendor_name",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="country",
    *                     type="string"
    *                 ),    
    *                 @OA\Property(
    *                     property="postal_code",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="add_1",
    *                     type="number"
    *                 ),  
    *                 @OA\Property(
    *                     property="about",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="founded_date",
    *                     type="string"
    *                 ),
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */
 
    /**
    * @OA\Delete(
    *     path="/admin/sponsor/delete/{id}",
    *     tags={"admin-sponsor"},
    *     summary="Delete Sponsor.",
    *     operationId="delete-sponsor",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of sponsor ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
     *       @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The sponsor Id ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */


    /**
    * @OA\Post(
    *     path="/admin/sponsor/add-sponsor",
    *     tags={"admin-sponsor"},
    *     summary="Admin Add Sponsor",
    *     operationId="addSponsor",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="email",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="role",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="mobile",
    *                     type="number"
    *                 ),
    *                 @OA\Property(
    *                     property="vendor_name",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="country",
    *                     type="string"
    *                 ),    
    *                 @OA\Property(
    *                     property="postal_code",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="add_1",
    *                     type="number"
    *                 ),  
    *                 @OA\Property(
    *                     property="about",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="founded_date",
    *                     type="string"
    *                 ),
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */
 

    /**
    * @OA\Post(
    *     path="/admin/sponsor/edit-sponsor/{id}",
    *     tags={"admin-sponsor"},
    *     summary="Edit Sponsor",
    *     operationId="adminEditSponsor",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *       @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The user Id ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="email",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="role",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="mobile",
    *                     type="number"
    *                 ),
    *                 @OA\Property(
    *                     property="vendor_name",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="country",
    *                     type="string"
    *                 ),    
    *                 @OA\Property(
    *                     property="postal_code",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="add_1",
    *                     type="number"
    *                 ),  
    *                 @OA\Property(
    *                     property="about",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="founded_date",
    *                     type="string"
    *                 ),
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */
 
    /**
     * @OA\Get(path="/admin/sponsor/silver/{id}",
     *   tags={"admin-sponsor"},
     *   summary="Set Silver Sponsor",
     *   description="",
     *   operationId="silverSponsor",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The user Id ",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/sponsor")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="sponsor not found")
     * )
    */ 

    /**
     * @OA\Get(path="/admin/sponsor/gold/{id}",
     *   tags={"admin-sponsor"},
     *   summary="Set Gold Sponsor",
     *   description="",
     *   operationId="goldSponsor",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The user Id ",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/sponsor")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="sponsor not found")
     * )
    */ 


        /**
     * @OA\Get(path="/admin/sponsor/platinum/{id}",
     *   tags={"admin-sponsor"},
     *   summary="Set platinum Sponsor",
     *   description="",
     *   operationId="platinumSponsor",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The user Id ",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/sponsor")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="sponsor not found")
     * )
    */ 


        /**
     * @OA\Get(path="/admin/sponsor/diamond/{id}",
     *   tags={"admin-sponsor"},
     *   summary="Set Diamond Sponsor",
     *   description="",
     *   operationId="diamondSponsor",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The user Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/sponsor")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="sponsor not found")
     * )
    */ 



        /**
     * @OA\Get(path="/admin/sponsor/remove-level/{id}",
     *   tags={"admin-sponsor"},
     *   summary="Remove Sponsor Level",
     *   description="",
     *   operationId="remove-level",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The user Id ",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/sponsor")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="sponsor not found")
     * )
    */ 



        /**
     * @OA\Get(path="/admin/sponsor/normal/{id}",
     *   tags={"admin-sponsor"},
     *   summary="Set Normal Sponsor",
     *   description="",
     *   operationId="normalSponsor",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The user Id ",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Sponsor")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Sponsor not found")
     * )
    */ 






    /** 
     * ===================================================
     *      ADMIN SUPPORT DOCUMENTATION 
     * =================================================== 
    */

    /**
    * @OA\Post(
    *     path="/admin/support/inquire",
    *     tags={"admin-support"},
    *     summary="Inquire",
    *     operationId="inquire",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="message",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */
 





    /** 
     * ===================================================
     *      ADMIN USER PAYMENT DOCUMENTATION 
     * =================================================== 
    */

    /**
     * @OA\Get(
     *   path="/admin/user-payment/list",
     *   tags={"admin-user-payment"},
     *   summary="Get Admin user payment List",
     *   description="",
     *   operationId="getAdminUserPaymentList",
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The page number",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="size",
     *     in="query",
     *     description="The rows per page",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="keyword",
     *     in="query",
     *     description="The title keyword",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="status",
     *     in="query",
     *     description="status",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="account_id",
     *     in="query",
     *     description="The account Id",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="payment_for",
     *     in="query",
     *     description="The payment purpose",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="payment_id",
     *     in="query",
     *     description="The Payment Id",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/user-payment")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="user-payment not found")
     * )
     */
    
    /**
    * @OA\Post(
    *     path="/admin/user-payment/create",
    *     tags={"admin-user-payment"},
    *     summary="Create user payment",
    *     operationId="createUserPayment",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="name",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="amount",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="file",
    *                     type="file"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */
 

    /**
    * @OA\Post(
    *     path="/admin/user-payment/update/{id}",
    *     tags={"admin-user-payment"},
    *     summary="Admin Update user payment",
    *     operationId="adminUpdateUserPayment",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *       @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The payment Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="name",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="amount",
    *                     type="string",
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */
 
    /**
    * @OA\Delete(
    *     path="/admin/user-payment/delete/{id}",
    *     tags={"admin-user-payment"},
    *     summary="Delete user payment.",
    *     operationId="delete-user-payment",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user-payment ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *       @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The payment Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ), 
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */
    

    /**
    * @OA\Delete(
    *     path="/admin/user-payment/hard-delete/{id}",
    *     tags={"admin-user-payment"},
    *     summary="Hard Delete user-payment.",
    *     operationId="hard-delete",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user-payment ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
     *       @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The payment Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */
    
    /**
     * @OA\Post(path="/admin/user-payment/approve/{id}",
     *   tags={"admin-user-payment"},
     *   summary="user-payment Approve",
     *   description="",
     *   operationId="approveuser-payment",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/user-payment")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="user-payment not found")
     * )
    */ 

    /**
     * @OA\Post(path="/admin/user-payment/reject/{id}",
     *   tags={"admin-user-payment"},
     *   summary="user payment reject",
     *   description="",
     *   operationId="reject-user-payment",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/user-payment")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="user-payment not found")
     * )
    */ 


    
    /** 
     * ===================================================
     *      ADMIN VENDOR DOCUMENTATION 
     * =================================================== 
    */

    /**
     * @OA\Get(
     *   path="/admin/vendor/list",
     *   tags={"admin-vendor"},
     *   summary="Get Admin Vendor List",
     *   description="",
     *   operationId="getAdminVendorList",
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The page number",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="size",
     *     in="query",
     *     description="The rows per page",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="keyword",
     *     in="query",
     *     description="The title keyword",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="status",
     *     in="query",
     *     description="status",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="account_id",
     *     in="query",
     *     description="The account Id",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="role",
     *     in="query",
     *     description="The vendor role",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="type",
     *     in="query",
     *     description="The vendor type",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Vendor")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Vendor not found")
     * )
     */

    
    /**
    * @OA\Post(
    *     path="/admin/vendor/add-vendor",
    *     tags={"admin-vendor"},
    *     summary="Admin Add Vendor",
    *     operationId="add-vendor",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="email",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="password",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="mobile",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="vendor_name",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="country",
    *                     type="string"
    *                 ),    
    *                 @OA\Property(
    *                     property="postal_code",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="add_1",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="about",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="founded_date",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */
 
    /**
    * @OA\Post(
    *     path="/admin/vendor/edit-vendor/{id}",
    *     tags={"admin-vendor"},
    *     summary="Admin Edit Vendor",
    *     operationId="edit-vendor",
    *     @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The vendor",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="email",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="mobile",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="vendor_name",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="country",
    *                     type="string"
    *                 ),    
    *                 @OA\Property(
    *                     property="postal_code",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="add_1",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="about",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="founded_date",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */
 

    /**
    * @OA\Post(
    *     path="/admin/vendor/update",
    *     tags={"admin-vendor"},
    *     summary="Admin Update Vendor",
    *     operationId="adminUpdateVendor",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The vendor",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="email",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="mobile",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="vendor_name",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="country",
    *                     type="string"
    *                 ),    
    *                 @OA\Property(
    *                     property="postal_code",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="add_1",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="about",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="founded_date",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */
 

  

 /**
    * @OA\Post(
    *     path="/admin/vendor/update-password",
    *     tags={"admin-vendor"},
    *     summary="Vendor Password Update ",
    *     operationId="passwordUpdate",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
  *                 @OA\Property(
    *                     property="password_current",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="password_new",
    *                     type="string"
    *                 ),  
    *                 @OA\Property(
    *                     property="password_confirm",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

     
    /**
    * @OA\Post(
    *     path="/admin/vendor/update-email",
    *     tags={"admin-vendor"},
    *     summary="Vendor Email Update ",
    *     operationId="UpdateEmail",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="email",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    
    /**
    * @OA\Post(
    *     path="/admin/vendor/update-mobile",
    *     tags={"admin-vendor"},
    *     summary="Vendor Mobile Update ",
    *     operationId="UpdateMobile",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="mobile_code",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="mobile",
    *                     type="number"
    *                )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

     
    /**
    * @OA\Post(
    *     path="/admin/vendor/update-settings/{id}",
    *     tags={"admin-vendor"},
    *     summary="Vendor Update Settings",
    *     operationId="update-settings",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *       @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The user Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ), 
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="carkee_level",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="level",
    *                     type="integer"
    *                ),   
    *                 @OA\Property(
    *                     property="account_id",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="club_code",
    *                     type="integer"
    *                ),    
    *                 @OA\Property(
    *                     property="member_expiry",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="enable_ads",
    *                     type="integer"
    *                ),    
    *                 @OA\Property(
    *                     property="is_one_approval",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="renewal_alert",
    *                     type="integer"
    *                ),    
    *                 @OA\Property(
    *                     property="skip_approval",
    *                     type="string"
    *                 )
    *            )
    *        )
    *    ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */

    /**
     * @OA\Post(path="/admin/vendor/approve/{id}",
     *   tags={"admin-vendor"},
     *   summary="Admin Approve Vendor",
     *   description="",
     *   operationId="approve-vendor",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The user Id ",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/vendor")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="vendor not found")
     * )
    */ 

    /**
     * @OA\Post(path="/admin/vendor/reject/{id}",
     *   tags={"admin-vendor"},
     *   summary="Admin Reject Vendor",
     *   description="",
     *   operationId="reject-vendor",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The user Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/user-payment")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="user-payment not found")
     * )
    */ 


    /**
     * @OA\Get(
     *   path="/admin/vendor/itemlist",
     *   tags={"admin-vendor"},
     *   summary="Get Admin Vendor Item List",
     *   description="",
     *   operationId="getVendorItemList",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of user ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
         *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The page number",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="size",
     *     in="query",
     *     description="The rows per page",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="keyword",
     *     in="query",
     *     description="The title keyword",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="status",
     *     in="query",
     *     description="status",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Parameter(
     *     name="account_id",
     *     in="query",
     *     description="The account Id",
     *     required=false,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),         
     *   @OA\Parameter(
     *     name="type",
     *     in="query",
     *     description="The vendor type",
     *     required=false,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),     
     *   @OA\Response(response=200, description="successful operation", @OA\Schema(ref="#/components/schemas/Vendor")),
     *   @OA\Response(response=400, description="Invalid access-token supplied"),
     *   @OA\Response(response=404, description="Vendor not found")
     * )
     */
 

    /**
    * @OA\Delete(
    *     path="/admin/vendor/delete/{id}",
    *     tags={"admin-vendor"},
    *     summary="Admin Delete Vendor.",
    *     operationId="delete-vendor",
    *       @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     description="The access token of vendor ",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ), 
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The vendor Id",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *   ),
    *    @OA\Response(
    *         response=200,
    *         description="Success",  
    *     ), 
    *  ) 
    */