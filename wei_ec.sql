/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50719
Source Host           : localhost:3306
Source Database       : wei_ec

Target Server Type    : MYSQL
Target Server Version : 50719
File Encoding         : 65001

Date: 2017-12-03 15:52:05
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for log_order
-- ----------------------------
DROP TABLE IF EXISTS `log_order`;
CREATE TABLE `log_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_num` char(15) NOT NULL COMMENT '订单号',
  `type` tinyint(1) unsigned NOT NULL COMMENT '类型 1.支付 2.发货 3.确认收货 4.申请退货 5.退货完成',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `data` text COMMENT '数据',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单日志表';

-- ----------------------------
-- Table structure for log_o_product
-- ----------------------------
DROP TABLE IF EXISTS `log_o_product`;
CREATE TABLE `log_o_product` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `o_product_id` int(10) NOT NULL COMMENT '订单号',
  `type` tinyint(1) unsigned NOT NULL COMMENT '类型 1.编辑',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `data` text COMMENT '数据',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单产品日志表';

-- ----------------------------
-- Table structure for log_o_return
-- ----------------------------
DROP TABLE IF EXISTS `log_o_return`;
CREATE TABLE `log_o_return` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `return_num` char(15) NOT NULL COMMENT '订单号',
  `type` tinyint(1) unsigned NOT NULL COMMENT '类型 1.商家确认 2.重新申请 3.用户发货 4.商家收货 5.商家退款',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `data` text COMMENT '数据',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单日志表';

-- ----------------------------
-- Table structure for log_s_package
-- ----------------------------
DROP TABLE IF EXISTS `log_s_package`;
CREATE TABLE `log_s_package` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `package_id` int(10) unsigned NOT NULL COMMENT '规格id',
  `type` tinyint(1) NOT NULL COMMENT '日志类型 1.编辑',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `data` text COMMENT '数据',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='产品规格日志表';

-- ----------------------------
-- Table structure for log_s_product
-- ----------------------------
DROP TABLE IF EXISTS `log_s_product`;
CREATE TABLE `log_s_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL COMMENT '产品id',
  `type` tinyint(1) NOT NULL COMMENT '日志类型 1.编辑 2.下架',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `data` text COMMENT '数据',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户id',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品日志表';

-- ----------------------------
-- Table structure for migration
-- ----------------------------
DROP TABLE IF EXISTS `migration`;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for order
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` int(10) unsigned NOT NULL COMMENT '供应商id',
  `order_num` char(15) NOT NULL COMMENT '订单号',
  `pay_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '支付状态 0.待支付 1.已支付',
  `deliver_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '发货状态 0.无状态 1.待发货 2.已发货',
  `revice_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '收货状态 0.无状态 1.待收货 2已收获 3退货中 4已退货',
  `user_id` int(10) NOT NULL COMMENT '创建人id',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '已回收 1.未回收 0已回收',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单表';

-- ----------------------------
-- Table structure for o_deliver
-- ----------------------------
DROP TABLE IF EXISTS `o_deliver`;
CREATE TABLE `o_deliver` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_num` char(15) NOT NULL COMMENT '订单号',
  `express_num` varchar(32) NOT NULL COMMENT '快递单号',
  `express_nu` varchar(32) NOT NULL COMMENT '快递公司',
  `express_data` text NOT NULL COMMENT '快递物流信息',
  `user_id` int(11) NOT NULL COMMENT '发货人id',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单发货信息';

-- ----------------------------
-- Table structure for o_payment
-- ----------------------------
DROP TABLE IF EXISTS `o_payment`;
CREATE TABLE `o_payment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_num` char(15) NOT NULL COMMENT '订单号',
  `serival_num` varchar(32) NOT NULL COMMENT '支付流水号',
  `pay_type` varchar(32) NOT NULL COMMENT '支付类型 wx',
  `pay_status` tinyint(1) unsigned NOT NULL COMMENT '支付状态 0.未完成 1.支付中 2.已完成',
  `user_id` int(10) NOT NULL COMMENT '支付人id',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单支付流水';

-- ----------------------------
-- Table structure for o_product
-- ----------------------------
DROP TABLE IF EXISTS `o_product`;
CREATE TABLE `o_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_num` char(15) NOT NULL COMMENT '订单编号',
  `product_id` int(10) NOT NULL COMMENT '产品id',
  `name` varchar(64) NOT NULL COMMENT '产品名称',
  `package` varchar(32) NOT NULL DEFAULT '' COMMENT '包装规格',
  `package_type` varchar(32) NOT NULL DEFAULT '' COMMENT '包装规格类型',
  `original_price` int(10) unsigned NOT NULL COMMENT '牌价',
  `price` int(10) NOT NULL COMMENT '出售价',
  `count` int(10) NOT NULL COMMENT '数量',
  `img_save_path` char(9) NOT NULL COMMENT '图片路径',
  `img_save_name` varchar(32) NOT NULL COMMENT '图片名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单产品';

-- ----------------------------
-- Table structure for o_return
-- ----------------------------
DROP TABLE IF EXISTS `o_return`;
CREATE TABLE `o_return` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `return_num` char(15) NOT NULL COMMENT '退货款单号',
  `order_num` char(15) NOT NULL,
  `price` int(10) NOT NULL DEFAULT '0' COMMENT '协议退款金额',
  `return_good` tinyint(1) NOT NULL DEFAULT '0' COMMENT '退货 0.无需退货 1.待发货 2.待收货 3.已收货',
  `repay_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '还款状态 0.无需退款 1.待退款 2.已退款',
  `audit_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '沟通状态 0.待商家同意 1.商家已同意 2.商家拒绝',
  `user_id` int(10) unsigned NOT NULL COMMENT '申请人',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单退货表';

-- ----------------------------
-- Table structure for o_return_char
-- ----------------------------
DROP TABLE IF EXISTS `o_return_char`;
CREATE TABLE `o_return_char` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `return_num` char(15) NOT NULL COMMENT '退货款单号',
  `data` varchar(500) NOT NULL COMMENT '交谈信息',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `source` tinyint(1) NOT NULL COMMENT '来源 1.客户 2.供应商',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单退货交谈';

-- ----------------------------
-- Table structure for o_return_char_img
-- ----------------------------
DROP TABLE IF EXISTS `o_return_char_img`;
CREATE TABLE `o_return_char_img` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `o_r_char_id` int(10) NOT NULL COMMENT '订单退货交谈id',
  `save_path` char(9) NOT NULL COMMENT '图片路径',
  `save_name` varchar(32) NOT NULL COMMENT '图片名称',
  `real_name` varchar(255) NOT NULL COMMENT '原始名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='退货款交谈图片';

-- ----------------------------
-- Table structure for o_return_deliver
-- ----------------------------
DROP TABLE IF EXISTS `o_return_deliver`;
CREATE TABLE `o_return_deliver` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `return_num` char(15) NOT NULL COMMENT '退货款单号',
  `express_num` varchar(32) NOT NULL COMMENT '快递单号',
  `express_nu` varchar(64) NOT NULL COMMENT '快递公司',
  `express_data` text COMMENT '快递数据',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='退货物流';

-- ----------------------------
-- Table structure for o_return_payment
-- ----------------------------
DROP TABLE IF EXISTS `o_return_payment`;
CREATE TABLE `o_return_payment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `return_num` char(15) NOT NULL COMMENT '退货款单号',
  `type` varchar(32) NOT NULL COMMENT '类型 wx 原路返回',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='退款流水';

-- ----------------------------
-- Table structure for o_revice
-- ----------------------------
DROP TABLE IF EXISTS `o_revice`;
CREATE TABLE `o_revice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_num` char(15) NOT NULL COMMENT '订单号',
  `province` varchar(32) NOT NULL COMMENT '省编码',
  `city` varchar(32) NOT NULL COMMENT '市编码',
  `address` varchar(255) NOT NULL COMMENT '详细地址',
  `contact_name` varchar(10) NOT NULL COMMENT '联系人名称',
  `contact_phone` varchar(32) NOT NULL COMMENT '联系人电话',
  `user_id` int(10) unsigned NOT NULL COMMENT '创建人id',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为默认地址 0.不是 1.是',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单收货地址';

-- ----------------------------
-- Table structure for supplier
-- ----------------------------
DROP TABLE IF EXISTS `supplier`;
CREATE TABLE `supplier` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '店铺名称',
  `tag` varchar(32) NOT NULL DEFAULT '' COMMENT '店铺表示，系统设置',
  `province` varchar(32) NOT NULL COMMENT '所在省编码',
  `city` varchar(32) NOT NULL COMMENT '所在市编码',
  `address` varchar(255) NOT NULL COMMENT '详细地址',
  `telephone` varchar(32) NOT NULL DEFAULT '' COMMENT '手机号码',
  `phone` varchar(32) NOT NULL DEFAULT '' COMMENT '固定电话',
  `email` varchar(32) NOT NULL DEFAULT '' COMMENT '联系邮箱',
  `audit_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '审核状态 0.无状态 1.待审核 2.已同意 3.已拒绝',
  `user_id` int(10) NOT NULL COMMENT '用户id',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效 1.有效 0.无效',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='供应商';

-- ----------------------------
-- Table structure for s_catalog
-- ----------------------------
DROP TABLE IF EXISTS `s_catalog`;
CREATE TABLE `s_catalog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` int(10) unsigned NOT NULL COMMENT '供应商id',
  `name` varchar(32) NOT NULL COMMENT '分类名称',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类型id',
  `user_id` int(10) NOT NULL COMMENT '创建人id',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `order` int(10) NOT NULL DEFAULT '0' COMMENT '排序值越小越靠前',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品类型';

-- ----------------------------
-- Table structure for s_package
-- ----------------------------
DROP TABLE IF EXISTS `s_package`;
CREATE TABLE `s_package` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL COMMENT '产品id',
  `supplier_id` int(11) NOT NULL COMMENT '供应商id',
  `package_type_id` int(11) NOT NULL COMMENT '规格类型id',
  `package` varchar(32) NOT NULL COMMENT '规格',
  `price` int(11) unsigned NOT NULL COMMENT '牌价',
  `count` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='产品规格';

-- ----------------------------
-- Table structure for s_package_type
-- ----------------------------
DROP TABLE IF EXISTS `s_package_type`;
CREATE TABLE `s_package_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL COMMENT '规格类型名称',
  `user_id` int(10) NOT NULL COMMENT '创建人id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='产品规格类型';

-- ----------------------------
-- Table structure for s_product
-- ----------------------------
DROP TABLE IF EXISTS `s_product`;
CREATE TABLE `s_product` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `number` varchar(32) NOT NULL COMMENT '商品编号',
  `name` varchar(64) NOT NULL COMMENT '商品名称',
  `price`  int(10) UNSIGNED NOT NULL COMMENT '商品价格',
  `supplier_id` int(10) NOT NULL COMMENT '供应商id',
  `description` text COMMENT '描述',
  `user_id` int(10) NOT NULL COMMENT '创建人id',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否上架 1.上架 0.下架',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='供应商商品';

-- ----------------------------
-- Table structure for s_product_catalog
-- ----------------------------
DROP TABLE IF EXISTS `s_product_catalog`;
CREATE TABLE `s_product_catalog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) unsigned NOT NULL,
  `catalog_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品分类关联表';

-- ----------------------------
-- Table structure for s_product_img
-- ----------------------------
DROP TABLE IF EXISTS `s_product_img`;
CREATE TABLE `s_product_img` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL COMMENT '产品id',
  `save_path` char(9) NOT NULL COMMENT '保存地址',
  `save_name` varchar(32) NOT NULL COMMENT '保存名称',
  `real_name` varchar(255) NOT NULL COMMENT '真实名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='产品图片';

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `openid` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '用户的唯一标识',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '用户的性别，值为1时是男性，值为2时是女性，值为0时是未知',
  `province` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '用户个人资料填写的省份',
  `city` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '普通用户个人资料填写的城市',
  `country` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '国家，如中国为CN',
  `headimgurl` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），用户没有头像时该项为空。若用户更换头像，原有头像URL将失效。',
  `privilege` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '用户特权信息，json 数组，如微信沃卡用户为（chinaunicom）',
  `unionid` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段。',
  PRIMARY KEY (`id`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

