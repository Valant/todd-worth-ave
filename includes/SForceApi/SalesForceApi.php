<?php

require_once ('soapclient/SforceEnterpriseClient.php');

class SalesForceApi {

    // Mode: 'dev' or 'prod'
    public $mode = 'dev';
    // shouldn't be null ( integer ) !
    public $update_version = 1;
    private $connection;
    private $productFieldsRelations = [
        'Boatname'                       => 'Name',
        'AskingPrice'                    => 'AskingPrice__c',
        'BeamFeet'                       => 'BeamFeet__c',
        'BeamMeters'                     => 'BeamMeters__c',
        'Builder'                        => 'Builder__c',
        'CaptainsName'                   => 'CaptainsName__c',
    //    'Classifications',
        'Condition'                      => 'Condition__c',
        'Currency'                       => 'Currency__c',
        'DescriptionBrokerTeaser'        => 'DescriptionBrokerTeaser__c',
        'DescriptionShowingInstructions' => 'DescriptionShowingInstructions__c',
        'LocationRegionName'             => 'LocationRegionName__c',
        'EngineManufacturer'             => 'EngineManufacturer__c',
        'Flag'                           => 'Flag__c',
        'ForSale'                        => 'For_Sale__c',
        'ProfileURL'                     => 'Image_URL__c',
        'Update_Version'                 => 'Update_Version__c',
        'GrossTonnage'                   => 'GrossTonnage__c',
        'HullHullDesigner'               => 'HullHullDesigner__c',
        'ListingDate'                    => 'ListingDate__c',
        'LOAMeters'                      => 'LOAMeters__c',
        'LOAFeet'                        => 'LOAFeet__c',
        'LocationRegionName'             => 'LocationRegionName__c',
        'MaxDraftFeet'                   => 'MaxDraftFeet__c',
        'MaxDraftMeters'                 => 'MaxDraftMeters__c',
        'MaxSpeedKnots'                  => 'MaxSpeedKnots__c',
        'Model'                          => 'Model__c',
        'ModelYear'                      => 'ModelYear__c',
        'NumBerths'                      => 'NumBerths__c',
        'NumCrewBerths'                  => 'NumCrewBerths__c',
        'NumCrewSleeps'                  => 'NumCrewSleeps__c',
        'NumSleeps'                      => 'NumSleeps__c',
        'PDFUrl'                         => 'PDFUrl__c',
        'PropulsionType'                 => 'PropulsionType__c',
        'SalesPerson'                    => 'SalesPerson__c',
        'SalesPersonCellPhone'           => 'SalesPersonCellPhone__c',
        'SalesPersonEmail'               => 'SalesPersonEmail__c',
        'SalesPersonPhone'               => 'SalesPersonPhone__c',
        'StateRooms'                     => 'StateRooms__c',
        'VesselType'                     => 'VesselType__c',
        'YearBuilt'                      => 'YearBuilt__c'
    ];

    const USER_NAME_PROD = "debi@worthavenueyachts.com";
    const PASSWORD_PROD = "De3344Gi";
    const SECURITY_TOKEN_PROD = "DJf8KLHMQjkChP6VUZjxTcWL";

    const USER_NAME_DEV      = "debi@worthavenueyachts.com.sandbox1";
    const PASSWORD_DEV       = "De3344Gi";
    const SECURITY_TOKEN_DEV = "DJf8KLHMQjkChP6VUZjxTcWL";

    public function __construct()
    {
        $this->connection = new SforceEnterpriseClient();
        if ( $this->mode == 'dev' ) {
            $this->connection->createConnection(__DIR__."/sandbox-enterprise.wsdl.xml");
        } else if( $this->mode == 'prod' ) {
            $this->connection->createConnection(__DIR__."/prod-enterprise.wsdl.xml");
        }

        try {
            if ( $this->mode == 'dev' ) {
                $this->connection->login(self::USER_NAME_DEV, self::PASSWORD_DEV . self::SECURITY_TOKEN_DEV);
            } else if( $this->mode == 'prod' ) {
                $this->connection->login(self::USER_NAME_PROD, self::PASSWORD_PROD . self::SECURITY_TOKEN_PROD);
            }
        } catch(Exception $e) {
            throw new Exception($e->getMessage(),'400');
        }
    }

    /**
     * @param object $data Yacht information
     * @return integer id  Id of added yacht or false if failed
     */
    public function addNewProduct($data)
    {
        $record[0] = new stdclass();
        foreach ($this->productFieldsRelations as $key => $productItem)
        {
            if ( isset( $data->{$key} ) && !empty( $data->{$key} ) )
            {
                if ( $key == 'LOAFeet' && $data->{$key} != '' ) {
                    $record[0]->{$productItem} = $this->convertFeet( $data->{$key} );
                } else {
                    $record[0]->{$productItem} = $data->{$key};
                }
            }
        }

        try {
            $result = $this->connection->create($record, 'Product2');
        }
        catch ( Exception $e ) {
            $message = $e->getMessage();
            return array( 'status' => 'error', 'message' => $message);
        }

        return array( 'status' => 'success', 'id' => $result[0]->id );
    }

    /**
     * @param integer $productId Product ID in SalesForce system
     * @param object $productData Yacht information
     */
    public function updateProduct($productId, $productData)
    {
        $record[0] = new stdclass();
        $record[0]->Id = $productId;
        foreach ($this->productFieldsRelations as $key => $productItem)
        {
            if ( isset( $productData->{$key} ) && !empty( $productData->{$key} ) )
            {
                if ( $key == 'LOAFeet' && $productData->{$key} != '' ) {
                    $record[0]->{$productItem} = $this->convertFeet( $productData->{$key} );
                } else {
                    $record[0]->{$productItem} = $productData->{$key};
                }
            }
        }

        try{
            $result = $this->connection->update($record, 'Product2');
        }
        catch (Exception $e) {

            $message = $e->getMessage();

            return array( 'status' => 'error', 'message' => $message);
        }

        return array( 'status' => 'success' );

    }

    /**
     * @param array $productIds  An array of product ids for removal.
     */
    public function deleteProducts($productIds)
    {
        $this->connection->delete($productIds);
    }

    /**
     * @param string $value is a string fith Feet.
    */
    public function convertFeet( $value )
    {
        if ( is_numeric( $value ) ) {
            return (integer)$value;
        } else {

            $position = strpos( $value, "'" );
            if ( $position ) {
                $convertedValue = substr( $value, 0, $position );
            } else {
                $convertedValue = 0;
            }

            return (integer)$convertedValue;
        }
    }
}
