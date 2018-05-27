<?php
declare(strict_types=1);

namespace Etechnologia\Platform\Todo\Behat;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use PHPUnit\Framework\Assert;
use Psr\Http\Message\ResponseInterface;
use Pwm\DeepEnd\DeepEnd;
use Symfony\Component\HttpKernel\KernelInterface;

class BaseContext implements KernelAwareContext
{
    /** @var TodoApiClient */
    private $client;

    /** @var DBHandler */
    private $dbHandler;

    /** @var DeepEnd[] */
    private $deepEnds;

    /** @var array[][] */
    private $entity = [];

    /** @var string */
    public $payload = '';

    /** @var ResponseInterface */
    public $response;

    public const RESPONSE_STATUS_FROM_HTTP_CODE = [
        'bad request' => 400,
        'not found' => 404,
        'server error' => 500,
    ];

    /**
     * @param TodoApiClient $client
     * @param DBHandler $dbHandler
     */
    public function __construct(TodoApiClient $client, DBHandler $dbHandler)
    {
        $this->client = $client;
        $this->dbHandler = $dbHandler;
    }

    /**
     * Sets Kernel instance.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel): void
    {
        $this->dbHandler->setRegistry($kernel->getContainer()->get('doctrine'));
    }

    /**
     * @AfterScenario
     */
    public function resetDB(): void
    {
        if ($this->deepEnds !== null && count($this->deepEnds) > 0) {
            foreach ($this->deepEnds as $entityName => $deepEnd) {
                $deletionOrder = $deepEnd->sortToMap();
                foreach ($deletionOrder as $entityId => $data) {
                    $this->dbHandler->deleteEntity($entityName, $data['table'], $data['colName'], $entityId);
                }
            }
        }
    }

    /**
     * @AfterScenario
     */
    public function resetPayload()
    {
        $this->payload = [];
    }

    /**
     * @param string $entityName
     * @param string $table
     * @param string $colName
     * @param int $internalId
     * @param array $entity
     */
    public function saveEntity(string $entityName, string $table, string $colName, int $internalId, array $entity): void
    {
        $this->entity[$table][$internalId] = $entity;
        if (! isset($this->deepEnds[$entityName])) {
            $this->deepEnds[$entityName] = new DeepEnd();
        }

        $this->deepEnds[$entityName]->add($entity[$colName], ['table' => $table, 'colName' => $colName]);
    }

    /**
     * @param string $table
     * @param int $internalId
     * @param array $entity
     */
    public function updateEntity(string $table, int $internalId, array $entity): void
    {
        $this->entity[$table][$internalId] = $entity;
    }

    /**
     * @param string $table
     * @param int $internalId
     * @return array
     */
    public function getEntity(string $table, int $internalId): array
    {
        return $this->entity[$table][$internalId];
    }

    /**
     * @param string $table
     * @return array
     */
    public function getEntityByTableName(string $table): array
    {
        return $this->entity[$table];
    }

    /**
     * @Given I have the following payload
     *
     * @param PyStringNode $payload
     */
    public function iHaveTheFollowingPayload(PyStringNode $payload)
    {
        $this->payload = $payload->getRaw();
    }

    /**
     * @Then I am shown a success response
     */
    public function iAmShownASuccessResponse(): void
    {
        Assert::assertSame(200, $this->response->getStatusCode());
    }

    /**
     * @Then I am shown a :httpStatus with code :internalCode
     * @param string $httpStatus
     * @param int $internalCode
     */
    public function iAmShownAnErrorResponse(string $httpStatus, int $internalCode): void
    {
        Assert::assertSame(self::RESPONSE_STATUS_FROM_HTTP_CODE[$httpStatus], $this->response->getStatusCode());
        Assert::assertSame($internalCode, RestClient::getResponseContentByKey($this->response, 'error')['code']);
    }

    /**
     * @Then I am shown a test success result
     */
    public function IAmShownATestSuccessResult(): void
    {
        Assert::assertTrue(true);
    }
}
