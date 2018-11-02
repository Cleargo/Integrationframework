<?php

namespace Cleargo\Integrationframeworks\Model\Component;
use Cleargo\Integrationframeworks\Logger\Logger;
use Magento\Framework\Filesystem\DirectoryList;
class DownloadMandrillMsg
{
    use BaseComponentTrait;

    protected $logger;

    protected $relationParams;

    protected $websiteId;

    protected $storeId;

    protected $scheduleLogLevel;

    protected $connector;

    protected $directoryList;

    protected $resourceConnection;

    const MANDRILL_MSG_TABLE = 'cleargo_mandrillmessage_mandrillmessage';


    public function __construct(
        Logger $logger,
        DirectoryList $directoryList,
        \Cleargo\MandrillMessage\Model\Connector $connector,
        \Magento\Framework\App\ResourceConnection $resourceConnection
)
    {
        $this->logger = $logger;
        $this->directoryList = $directoryList;
        $this->connector = $connector;
        $this->resourceConnection=$resourceConnection;

    }



    public function execute()
    {
        echo "start downloading msg from Mandrill\n";

        try {
            $lastRecordDate = $this->getLastRecordDate();
            $lastRecordDateInYYMMDD = date("Y-m-d",$lastRecordDate);
            //\Zend_Debug::dump($this->relationParams->date_to);die();
            $today = date("Y-m-d");
            $limit = $this->relationParams->limit? $this->relationParams->limit : '';
            if (isset($this->relationParams->date_from)){
                $date_from = $this->relationParams->date_from;
            } else {
                $date_from = $lastRecordDateInYYMMDD;
            }

            if (isset($this->relationParams->date_to)){
                $date_to = $this->relationParams->date_to;
            } else {
                $date_to = $today;
            }

            $params = array(
                "query" => "NOT email:aigle.com.hk"
            );
            if ($limit){
                $params['limit'] = $limit;
            }
            if ($date_from){
                $params['date_from'] = $date_from;
            }
            if ($date_to){
                $params['date_to'] = $date_to;
            }
            $this->logger->info(json_encode($params));
            $this->connector->processAndSave($params);

        } catch (\Exception $e){
            echo "dl mandrill msg error\n";
            $this->logger->info(json_encode($e->getMessage()));
            \Zend_Debug::dump($e->getMessage());
            var_dump($this->relationParams);
            return;
        }
    }

    private function getLastRecordDate()
    {
        $connection = $this->resourceConnection->getConnection();
        $query = $connection->prepare('select * from '.self::MANDRILL_MSG_TABLE.'  order by ts DESC LIMIT 1');

        //$query->bindValue(1, (string)$api_url);

        $query->execute();
        $result = $query->fetch();

        return isset($result['ts'])? $result['ts'] : '';
    }


}