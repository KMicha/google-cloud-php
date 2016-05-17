<?php
/*
 * Copyright 2016 Google Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except
 * in compliance with the License. You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed under the License
 * is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express
 * or implied. See the License for the specific language governing permissions and limitations under
 * the License.
 */

/*
 * GENERATED CODE WARNING
 * This file was generated from the file
 * https://github.com/google/googleapis/blob/master/google/pubsub/v1/pubsub.proto
 * and updates to that file get reflected here through a refresh process.
 */

namespace Google\Pubsub\V1;

use Google\GAX\AgentHeaderDescriptor;
use Google\GAX\ApiCallable;
use Google\GAX\CallSettings;
use Google\GAX\GrpcBootstrap;
use Google\GAX\GrpcConstants;
use Google\GAX\PageStreamingDescriptor;
use Google\GAX\PathTemplate;
use google\pubsub\v1\DeleteTopicRequest;
use google\pubsub\v1\GetTopicRequest;
use google\pubsub\v1\ListTopicSubscriptionsRequest;
use google\pubsub\v1\ListTopicsRequest;
use google\pubsub\v1\PublishRequest;
use google\pubsub\v1\PublisherClient;
use google\pubsub\v1\Topic;



/**
 * Service Description: The service that an application uses to manipulate topics, and to send
 * messages to a topic.
 *
 * This class provides the ability to make remote calls to the backing service through method
 * calls that map to API methods.
 *
 * Many parameters require resource names to be formatted in a particular way. To assist
 * with these names, this class includes a format method for each type of name, and additionally
 * a parse method to extract the individual identifiers contained within names that are
 * returned.
 */
class PublisherApi
{
    /**
     * The default address of the service.
     */
    const SERVICE_ADDRESS = 'pubsub-experimental.googleapis.com';

    /**
     * The default port of the service.
     */
    const DEFAULT_SERVICE_PORT = 443;

    /**
     * The default timeout for non-retrying methods.
     */
    const DEFAULT_TIMEOUT_MILLIS = 30000;

    const _GAX_VERSION = '0.1.0';
    const _CODEGEN_NAME = 'GAPIC';
    const _CODEGEN_VERSION = '0.0.0';

    private static $projectNameTemplate;
    private static $topicNameTemplate;

    private $grpcBootstrap;
    private $stub;
    private $scopes;
    private $defaultCallSettings;
    private $descriptors;

    /**
     * Formats a string containing the fully-qualified path to represent
     * a project resource.
     */
    public static function formatProjectName($project)
    {
        return self::getProjectNameTemplate()->render([
            'project' => $project]);
    }

    /**
     * Formats a string containing the fully-qualified path to represent
     * a topic resource.
     */
    public static function formatTopicName($project, $topic)
    {
        return self::getTopicNameTemplate()->render([
            'project' => $project, 'topic' => $topic]);
    }

    /**
     * Parses the project from the given fully-qualified path which
     * represents a project resource.
     */
    public static function parseProjectFromProjectName($projectName)
    {
        return self::getProjectNameTemplate()->match($projectName)['project'];
    }

    /**
     * Parses the project from the given fully-qualified path which
     * represents a topic resource.
     */
    public static function parseProjectFromTopicName($topicName)
    {
        return self::getTopicNameTemplate()->match($topicName)['project'];
    }

    /**
     * Parses the topic from the given fully-qualified path which
     * represents a topic resource.
     */
    public static function parseTopicFromTopicName($topicName)
    {
        return self::getTopicNameTemplate()->match($topicName)['topic'];
    }


    private static function getProjectNameTemplate()
    {
        if (self::$projectNameTemplate == null) {
            self::$projectNameTemplate = new PathTemplate("projects/{project}");
        }
        return self::$projectNameTemplate;
    }

    private static function getTopicNameTemplate()
    {
        if (self::$topicNameTemplate == null) {
            self::$topicNameTemplate = new PathTemplate("projects/{project}/topics/{topic}");
        }
        return self::$topicNameTemplate;
    }


    private static function getPageStreamingDescriptors()
    {
        $listTopicsPageStreamingDescriptor =
                new PageStreamingDescriptor([
                    'requestPageTokenField' => 'page_token',
                    'responsePageTokenField' => 'next_page_token',
                    'resourceField' => 'topics']);
        $listTopicSubscriptionsPageStreamingDescriptor =
                new PageStreamingDescriptor([
                    'requestPageTokenField' => 'page_token',
                    'responsePageTokenField' => 'next_page_token',
                    'resourceField' => 'subscriptions']);

        $pageStreamingDescriptors = [
            'listTopics' => $listTopicsPageStreamingDescriptor,
            'listTopicSubscriptions' => $listTopicSubscriptionsPageStreamingDescriptor
        ];
        return $pageStreamingDescriptors;
    }

    // TODO(garrettjones): add channel (when supported in gRPC)
    /**
     * Constructor.
     *
     * @param array $options {
     *     Optional. Options for configuring the service API wrapper.
     *
     *     @type string $serviceAddress The domain name of the API remote host.
     *                                  Default 'pubsub-experimental.googleapis.com'.
     *     @type mixed $port The port on which to connect to the remote host. Default 443.
     *     @type Grpc\ChannelCredentials $sslCreds
     *           A `ChannelCredentials` for use with an SSL-enabled channel.
     *           Default: a credentials object returned from
     *           Grpc\ChannelCredentials::createSsl()
     *     @type array $scopes A string array of scopes to use when acquiring credentials.
     *                         Default the scopes for the Google Cloud Pub/Sub API.
     *     @type array $retryingOverride
     *           An associative array of string => RetryOptions, where the keys
     *           are method names (e.g. 'createFoo'), that overrides default retrying
     *           settings. A value of null indicates that the method in question should
     *           not retry.
     *     @type int $timeoutMillis The timeout in milliseconds to use for calls
     *                              that don't use retries. For calls that use retries,
     *                              set the timeout in RetryOptions.
     *                              Default: 30000 (30 seconds)
     *     @type string $appName The codename of the calling service. Default 'gax'.
     *     @type string $appVersion The version of the calling service.
     *                              Default: the current version of GAX.
     * }
     */
    public function __construct($options = [])
    {
        $defaultScopes = [
            'https://www.googleapis.com/auth/pubsub',
            'https://www.googleapis.com/auth/cloud-platform'
        ];
        $defaultOptions = [
            'serviceAddress' => self::SERVICE_ADDRESS,
            'port' => self::DEFAULT_SERVICE_PORT,
            'scopes' => $defaultScopes,
            'retryingOverride' => null,
            'timeoutMillis' => self::DEFAULT_TIMEOUT_MILLIS,
            'appName' => 'gax',
            'appVersion' => self::_GAX_VERSION];
        $options = array_merge($defaultOptions, $options);

        $headerDescriptor = new AgentHeaderDescriptor([
            'clientName' => $options['appName'],
            'clientVersion' => $options['appVersion'],
            'codeGenName' => self::_CODEGEN_NAME,
            'codeGenVersion' => self::_CODEGEN_VERSION,
            'gaxVersion' => self::_GAX_VERSION,
            'phpVersion' => phpversion(),
        ]);

        $defaultDescriptors = ['headerDescriptor' => $headerDescriptor];
        $this->descriptors = [
            'createTopic' => $defaultDescriptors,
            'publish' => $defaultDescriptors,
            'getTopic' => $defaultDescriptors,
            'listTopics' => $defaultDescriptors,
            'listTopicSubscriptions' => $defaultDescriptors,
            'deleteTopic' => $defaultDescriptors
        ];
        $pageStreamingDescriptors = self::getPageStreamingDescriptors();
        foreach ($pageStreamingDescriptors as $method => $pageStreamingDescriptor) {
            $this->descriptors[$method]['pageStreamingDescriptor'] = $pageStreamingDescriptor;
        }

        // TODO load the client config in a more package-friendly way
        $clientConfigJsonString = file_get_contents('./resources/publisher_client_config.json');
        $clientConfig = json_decode($clientConfigJsonString, true);
        $this->defaultCallSettings =
                CallSettings::load('google.pubsub.v1.Publisher',
                                   $clientConfig,
                                   $options['retryingOverride'],
                                   GrpcConstants::getStatusCodeNames(),
                                   $options['timeoutMillis']);

        $this->scopes = $options['scopes'];

        $generatedCreateStub = function($hostname, $opts) {
            return new PublisherClient($hostname, $opts);
        };
        $createStubOptions = [];
        if (!empty($options['sslCreds'])) {
            $createStubOptions['sslCreds'] = $options['sslCreds'];
        }
        $this->grpcBootstrap = GrpcBootstrap::defaultInstance();
        $this->stub = $this->grpcBootstrap->createStub(
            $generatedCreateStub,
            $options['serviceAddress'],
            $options['port'],
            $createStubOptions);
    }


    /**
     * Creates the given topic with the given name.
     *
     * @param string $name The name of the topic. It must have the format
     * `"projects/{project}/topics/{topic}"`. `{topic}` must start with a letter,
     * and contain only letters (`[A-Za-z]`), numbers (`[0-9]`), dashes (`-`),
     * underscores (`_`), periods (`.`), tildes (`~`), plus (`+`) or percent
     * signs (`%`). It must be between 3 and 255 characters in length, and it
     * must not start with `"goog"`.
     * @param array $optionalArgs {
     *     Optional. There are no optional parameters for this method yet;
     *               this $optionalArgs parameter reserves a spot for future ones.
     * }
     * @param array $callSettings {
     *    Optional.
     *    @type Google\GAX\RetrySettings $retrySettings
     *          Retry settings to use for this call. If present, then
     *          $timeout is ignored.
     *    @type integer $timeoutMillis
     *          Timeout to use for this call. Only used if $retrySettings
     *          is not set.
     * }
     *
     * @return google\pubsub\v1\Topic
     *
     * @throws Google\GAX\ApiException if the remote call fails
     */
    public function createTopic($name, $optionalArgs = [], $callSettings = [])
    {
        $request = new Topic();
        $request->setName($name);

        $mergedSettings = $this->defaultCallSettings['createTopic']->merge(
            new CallSettings($callSettings));
        $callable = ApiCallable::createApiCall(
            $this->stub, 'CreateTopic', $mergedSettings, $this->descriptors['createTopic']);
        return $callable(
            $request,
            [],
            ['call_credentials_callback' => $this->createCredentialsCallback()]);
    }

    /**
     * Adds one or more messages to the topic. Returns `NOT_FOUND` if the topic
     * does not exist. The message payload must not be empty; it must contain
     *  either a non-empty data field, or at least one attribute.
     *
     * @param string $topic The messages in the request will be published on this topic.
     * @param array $messages The messages to publish.
     * @param array $optionalArgs {
     *     Optional. There are no optional parameters for this method yet;
     *               this $optionalArgs parameter reserves a spot for future ones.
     * }
     * @param array $callSettings {
     *    Optional.
     *    @type Google\GAX\RetrySettings $retrySettings
     *          Retry settings to use for this call. If present, then
     *          $timeout is ignored.
     *    @type integer $timeoutMillis
     *          Timeout to use for this call. Only used if $retrySettings
     *          is not set.
     * }
     *
     * @return google\pubsub\v1\PublishResponse
     *
     * @throws Google\GAX\ApiException if the remote call fails
     */
    public function publish($topic, $messages, $optionalArgs = [], $callSettings = [])
    {
        $request = new PublishRequest();
        $request->setTopic($topic);
        foreach ($messages as $elem) {
            $request->addMessages($elem);
        }

        $mergedSettings = $this->defaultCallSettings['publish']->merge(
            new CallSettings($callSettings));
        $callable = ApiCallable::createApiCall(
            $this->stub, 'Publish', $mergedSettings, $this->descriptors['publish']);
        return $callable(
            $request,
            [],
            ['call_credentials_callback' => $this->createCredentialsCallback()]);
    }

    /**
     * Gets the configuration of a topic.
     *
     * @param string $topic The name of the topic to get.
     * @param array $optionalArgs {
     *     Optional. There are no optional parameters for this method yet;
     *               this $optionalArgs parameter reserves a spot for future ones.
     * }
     * @param array $callSettings {
     *    Optional.
     *    @type Google\GAX\RetrySettings $retrySettings
     *          Retry settings to use for this call. If present, then
     *          $timeout is ignored.
     *    @type integer $timeoutMillis
     *          Timeout to use for this call. Only used if $retrySettings
     *          is not set.
     * }
     *
     * @return google\pubsub\v1\Topic
     *
     * @throws Google\GAX\ApiException if the remote call fails
     */
    public function getTopic($topic, $optionalArgs = [], $callSettings = [])
    {
        $request = new GetTopicRequest();
        $request->setTopic($topic);

        $mergedSettings = $this->defaultCallSettings['getTopic']->merge(
            new CallSettings($callSettings));
        $callable = ApiCallable::createApiCall(
            $this->stub, 'GetTopic', $mergedSettings, $this->descriptors['getTopic']);
        return $callable(
            $request,
            [],
            ['call_credentials_callback' => $this->createCredentialsCallback()]);
    }

    /**
     * Lists matching topics.
     *
     * @param string $project The name of the cloud project that topics belong to.
     * @param array $optionalArgs {
     *     Optional. There are no optional parameters for this method yet;
     *               this $optionalArgs parameter reserves a spot for future ones.
     * }
     * @param array $callSettings {
     *    Optional.
     *    @type Google\GAX\RetrySettings $retrySettings
     *          Retry settings to use for this call. If present, then
     *          $timeout is ignored.
     *    @type integer $timeoutMillis
     *          Timeout to use for this call. Only used if $retrySettings
     *          is not set.
     * }
     *
     * @return Google\GAX\PageAccessor
     *
     * @throws Google\GAX\ApiException if the remote call fails
     */
    public function listTopics($project, $optionalArgs = [], $callSettings = [])
    {
        $request = new ListTopicsRequest();
        $request->setProject($project);

        $mergedSettings = $this->defaultCallSettings['listTopics']->merge(
            new CallSettings($callSettings));
        $callable = ApiCallable::createApiCall(
            $this->stub, 'ListTopics', $mergedSettings, $this->descriptors['listTopics']);
        return $callable(
            $request,
            [],
            ['call_credentials_callback' => $this->createCredentialsCallback()]);
    }

    /**
     * Lists the name of the subscriptions for this topic.
     *
     * @param string $topic The name of the topic that subscriptions are attached to.
     * @param array $optionalArgs {
     *     Optional. There are no optional parameters for this method yet;
     *               this $optionalArgs parameter reserves a spot for future ones.
     * }
     * @param array $callSettings {
     *    Optional.
     *    @type Google\GAX\RetrySettings $retrySettings
     *          Retry settings to use for this call. If present, then
     *          $timeout is ignored.
     *    @type integer $timeoutMillis
     *          Timeout to use for this call. Only used if $retrySettings
     *          is not set.
     * }
     *
     * @return Google\GAX\PageAccessor
     *
     * @throws Google\GAX\ApiException if the remote call fails
     */
    public function listTopicSubscriptions($topic, $optionalArgs = [], $callSettings = [])
    {
        $request = new ListTopicSubscriptionsRequest();
        $request->setTopic($topic);

        $mergedSettings = $this->defaultCallSettings['listTopicSubscriptions']->merge(
            new CallSettings($callSettings));
        $callable = ApiCallable::createApiCall(
            $this->stub, 'ListTopicSubscriptions', $mergedSettings, $this->descriptors['listTopicSubscriptions']);
        return $callable(
            $request,
            [],
            ['call_credentials_callback' => $this->createCredentialsCallback()]);
    }

    /**
     * Deletes the topic with the given name. Returns `NOT_FOUND` if the topic
     * does not exist. After a topic is deleted, a new topic may be created with
     * the same name; this is an entirely new topic with none of the old
     * configuration or subscriptions. Existing subscriptions to this topic are
     * not deleted, but their `topic` field is set to `_deleted-topic_`.
     *
     * @param string $topic Name of the topic to delete.
     * @param array $optionalArgs {
     *     Optional. There are no optional parameters for this method yet;
     *               this $optionalArgs parameter reserves a spot for future ones.
     * }
     * @param array $callSettings {
     *    Optional.
     *    @type Google\GAX\RetrySettings $retrySettings
     *          Retry settings to use for this call. If present, then
     *          $timeout is ignored.
     *    @type integer $timeoutMillis
     *          Timeout to use for this call. Only used if $retrySettings
     *          is not set.
     * }
     *
     * @throws Google\GAX\ApiException if the remote call fails
     */
    public function deleteTopic($topic, $optionalArgs = [], $callSettings = [])
    {
        $request = new DeleteTopicRequest();
        $request->setTopic($topic);

        $mergedSettings = $this->defaultCallSettings['deleteTopic']->merge(
            new CallSettings($callSettings));
        $callable = ApiCallable::createApiCall(
            $this->stub, 'DeleteTopic', $mergedSettings, $this->descriptors['deleteTopic']);
        return $callable(
            $request,
            [],
            ['call_credentials_callback' => $this->createCredentialsCallback()]);
    }


    /**
     * Initiates an orderly shutdown in which preexisting calls continue but new
     * calls are immediately cancelled.
     */
    public function close()
    {
        $this->stub->close();
    }

    private function createCredentialsCallback()
    {
        return $this->grpcBootstrap->createCallCredentialsCallback($this->scopes);
    }
}
