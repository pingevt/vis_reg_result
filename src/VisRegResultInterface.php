<?php

namespace Drupal\vis_reg_result;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a vis-reg result entity type.
 */
interface VisRegResultInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
