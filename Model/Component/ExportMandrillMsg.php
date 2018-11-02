<?php

namespace Cleargo\Integrationframeworks\Model\Component;
use Cleargo\Integrationframeworks\Logger\Logger;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Io\File;
class ExportMandrillMsg
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

    protected $io;

    const MANDRILL_MSG_TABLE = 'cleargo_mandrillmessage_mandrillmessage';


    public function __construct(
        Logger $logger,
        DirectoryList $directoryList,
        \Cleargo\MandrillMessage\Model\Connector $connector,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        File $io
)
    {
        $this->logger = $logger;
        $this->directoryList = $directoryList;
        $this->connector = $connector;
        $this->resourceConnection=$resourceConnection;
        $this->io = $io;
    }



    public function execute()
    {
        echo "start exporting Mandrillmsg from magento\n";

        try {

            //set the export path
            $exportPath = $this->relationParams->export_path;
            $outputDir = $this->directoryList->getRoot() . $exportPath;

            //get exported data
            $records = $this->getReadyForExportData();

            if ($records && count($records)>0) {

                //prepare file name
                $currentTime = time();
                $fileTime = date("Ymd_His", $currentTime);
                $fileName = 'p_and_c_trx_email_' . $fileTime . '.txt';
                $outputFile = fopen($outputDir . $fileName, "w");


                \Zend_Debug::dump($exportPath);

                $result = array();

                $exported_ids = array();
                foreach ($records as $record) {

                    $content = json_decode($record['content'], 1);

                    $content['magento_member_id'] = $record['magento_member_id'];
                    $content['vip_no'] = $record['vip_no'];
                    //write to file

                    $result[] = $content;
                    $exported_ids[] = $record['mandrillmessage_id'];

                }
                fwrite($outputFile, json_encode($result));
                fclose($outputFile);


                //update DB state
                if ($exported_ids && count($exported_ids) > 0) {
                    $this->updateExportState($exported_ids, 'exported');
                }

            }


        } catch (\Exception $e){
            echo "export mandrill msg error {$e->getMessage()}\n";
            var_dump($this->relationParams);
            return;
        }
    }

    private function getReadyForExportData()
    {
        $connection = $this->resourceConnection->getConnection();
        $query = $connection->prepare('select * from '.self::MANDRILL_MSG_TABLE.' where state is null  order by ts ASC');

        //$query->bindValue(1, (string)$api_url);

        $query->execute();
        $result = $query->fetchAll();

        return $result;
    }

    private function updateExportState($exported_ids, $state='exported'){
        $connection = $this->resourceConnection->getConnection();
        $ids = implode(",",$exported_ids);
        $query = $connection->prepare('update  '.self::MANDRILL_MSG_TABLE." set state='$state' where mandrillmessage_id  in ($ids)");
        $result = $query->execute();
        return $result;
    }

}