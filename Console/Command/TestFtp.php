<?php
namespace Cleargo\Integrationframeworks\Console\Command;
use Magento\Backend\App\Area\FrontNameResolver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\ObjectManagerFactory;

class TestFtp extends Command
{

    const NAME_ARGUMENT = "name";
    const NAME_OPTION = "option";
    /**
     * @var ObjectManagerFactory
     */
    private $objectManagerFactory;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Constructor
     * @param ObjectManagerFactory $objectManagerFactory
     */
    public function __construct(ObjectManagerFactory $objectManagerFactory)
    {
        $this->objectManagerFactory = $objectManagerFactory;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $name = $input->getArgument(self::NAME_ARGUMENT);
        $option = $input->getOption(self::NAME_OPTION);
        $output->writeln("Test Model " . $name);

        /*
        $testModel = $this->getObjectManager()->create('Cleargo\Integrationframeworks\Model\WorkflowComponentDefinition');
        $testModel->load(1);
         \Zend_Debug::dump($testModel->getData());
        var_dump("---------------------- end of model -------------------- ");
        $testCollection = $this->getObjectManager()->create('Cleargo\Integrationframeworks\Model\ResourceModel\WorkflowComponentDefinition\Collection');
        $testCollection->addFieldToFilter("workflowcomponentdefinition_id",array("in"=>array(1,3)));
        if (count($testCollection)){
            foreach ($testCollection as $c){
                \Zend_Debug::dump($c->getData());
            }
        }*/

        $testModel = $this->getObjectManager()->create('Cleargo\Integrationframeworks\Model\Component\UploadFileToFTP');
        $param= "{\"ftp_host\":\"ftp://128.12.123.12\",\"ftp_user\":\"cleargo\",\"ftp_pw\":\"test123\",\"secure_type\":\"no-ssl\",\"file_pattern\":\"^shipment_[Ymd.xml\"}";
        $testModel->upload($param);

        //\Zend_Debug::dump($testModel->getData());



    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("cleargo_integrationframeworks:testFtp");
        $this->setDescription("test model");
        $this->setDefinition([
            new InputArgument(self::NAME_ARGUMENT, InputArgument::OPTIONAL, "Name"),
            new InputOption(self::NAME_OPTION, "-a", InputOption::VALUE_NONE, "Option functionality")
        ]);
        parent::configure();
    }


    /**
     * Gets initialized object manager
     *
     * @return ObjectManagerInterface
     */
    protected function getObjectManager()
    {
        if (null == $this->objectManager) {
            $area = FrontNameResolver::AREA_CODE;
            $this->objectManager = $this->objectManagerFactory->create($_SERVER);
            /** @var \Magento\Framework\App\State $appState */
            $appState = $this->objectManager->get('Magento\Framework\App\State');
            $appState->setAreaCode($area);
            $configLoader = $this->objectManager->get('Magento\Framework\ObjectManager\ConfigLoaderInterface');
            $this->objectManager->configure($configLoader->load($area));
        }
        return $this->objectManager;
    }
}
