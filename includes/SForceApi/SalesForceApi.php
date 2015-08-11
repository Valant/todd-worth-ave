<?php

require_once ('soapclient/SforceEnterpriseClient.php');

class SalesForceApi {

    public $mode = 'dev';
    public $SFSyncVersion = 1;
    private $connection;
    private $productFieldsRelations = [
        'Boatname'                       => 'Name',
        'AskingPrice'                    => 'AskingPrice__c',
        'BeamFeet'                       => 'BeamFeet__c',
        'BeamMeters'                     => 'BeamMeters__c',
        'Builder'                        => 'Builders__c',
//        'Builder'                        => 'Builder__c',
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
        'Image_URL'                      => 'Image_URL__c',
        'GrossTonnage'                   => 'GrossTonnage__c',
        'HullHullDesigner'               => 'HullHullDesigner__c',
        'ListingDate'                    => 'ListingDate__c',
        'LOAMeters'                      => 'LOAMeters__c',
        'LOAFeet'                        => 'LOAFeet__c',
        'LocationRegionName'             => 'Regions__c',
//        'LocationRegionName'             => 'LocationRegionName__c',
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

    public function __construct($mode)
    {
        $this->mode = $mode;

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

        try {
            $data = $this->_formatData($data);

            foreach ($this->productFieldsRelations as $key => $productItem)
            {
                if ( isset( $data->{$key} ) && !empty( $data->{$key} ) )
                {
                    $record[0]->{$productItem} = $data->{$key};
                }
            }

            $result = $this->connection->create($record, 'Product2');
        } catch ( Exception $e ) {
            $message = $e->getMessage();
            return array( 'status' => 'error', 'message' => $message);
        }

        if (!empty($result[0]) && $result[0]->success && $result[0]->id) {
            return array( 'status' => 'success', 'id' => $result[0]->id );
        } else {
            return array( 'status' => 'error', 'message' => json_encode($result) );
        }
    }

    /**
     * @param integer $productId Product ID in SalesForce system
     * @param object $productData Yacht information
     */
    public function updateProduct($productId, $productData)
    {
        $record[0] = new stdclass();
        $record[0]->Id = $productId;

        try {
            $productData = $this->_formatData($productData);

            foreach ($this->productFieldsRelations as $key => $productItem)
            {
                if ( isset( $productData->{$key} ) && !empty( $productData->{$key} ) )
                {
                    $record[0]->{$productItem} = $productData->{$key};
                }
            }

            $result = $this->connection->update($record, 'Product2');
        }
        catch (Exception $e) {

            $message = $e->getMessage();

            return array( 'status' => 'error', 'message' => $message);
        }

        if (!empty($result[0]) && $result[0]->success && $result[0]->id) {
            return array( 'status' => 'success', 'id' => $result[0]->id );
        } else {
            return array( 'status' => 'error', 'message' => json_encode($result) );
        }
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

    private function _formatData ($data)
    {
        $data->AskingPrice = (int) $data->AskingPrice;
        $data->DescriptionShowingInstructions = substr($data->DescriptionShowingInstructions, 0, 250);
        $data->LOAFeet = $this->convertFeet( $data->LOAFeet );
        $data->Builder = $this->_getBuilderId($data->Builder);
        $data->LocationRegionName = $this->_getRegionId($data->LocationRegionName);

        return $data;
    }

    public function getSyncVersion()
    {
        return $this->SFSyncVersion;
    }

    public function getSyncVersionKey()
    {
        if ( $this->mode == 'dev' ) {
            return 'SFSyncVersion_sandbox';
        } else if( $this->mode == 'prod' ) {
            return 'SFSyncVersion';
        }
    }

    public function getSyncIdKey()
    {
        if ( $this->mode == 'dev' ) {
            return 'SFProductId_sandbox';
        } else if( $this->mode == 'prod' ) {
            return 'SFProductId';
        }
    }

    public function setSyncMode( $mode )
    {
        $this->mode = $mode;
    }

    public function setSyncVersion( $version )
    {
        $this->SFSyncVersion = $version;
    }

    private function _getBuilderId ( $builderName ) {

        $result = $this->connection->query("select Id from Builder__c where name = '".str_replace("'","\'",$builderName)."'");

        foreach ($result as $key=>$builder) {
            return $builder->Id;
        }

        // else create record
        $record[0] = new stdclass();
        $record[0]->name = $builderName;
        $result = $this->connection->create($record, 'Builder__c');

        if (!empty($result[0]) && $result[0]->success && $result[0]->id) {
            return $result[0]->id;
        } else {
            throw new Exception(json_encode($result));
        }
    }

    private function _getRegionId ( $regionName ) {

        $result = $this->connection->query("select Id from Region__c where name = '".str_replace("'","\'",$regionName)."'");

        foreach ($result as $key=>$region) {
            return $region->Id;
        }

        // else create record
        $record[0] = new stdclass();
        $record[0]->name = $regionName;
        $result = $this->connection->create($record, 'Region__c');

        if (!empty($result[0]) && $result[0]->success && $result[0]->id) {
            return $result[0]->id;
        } else {
            throw new Exception(json_encode($result));
        }
    }
}
