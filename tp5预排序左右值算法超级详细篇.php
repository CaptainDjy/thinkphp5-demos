一个功能需要用到预排序左右值算法，网上找了很多，但是都不详细，主要的转移都没有，所以今天来分享个令人头疼的预排序

封装成了一个类，直接调用就可以了

<?php
namespace mptta;
/**
 * 预排序遍历树算法(modified preorder tree traversal algorithm)
 */
use think\Db;

class MPTTA
{
    public $options = [
        'table' => 'user',
        'pk' => 'id',
        'lft' => 'lft',
        'rgt' => 'rgt',
        'parent_id' => 'parent_id',
        'node_status' => 'node_status'
    ];

    /**
     * 添加节点
     * 我们选择在父节点尾部插入新节点
     * @param int $parent_node 父节点id
     * @param int $node_id        要插入的节点id
     */
    public function installNode($node_id, $parent_node_id)
    {
        $table = $this->options['table'];
        // 先取出父节点的信息
        if ($parent = Db::query("SELECT * FROM `$table` WHERE `{$this->options['pk']}`=$parent_node_id")) {
            $parent = $parent[0];
        }

        // 再取出子节点的信息
        if ($node = Db::query("SELECT * FROM `$table` WHERE `{$this->options['pk']}`=$node_id")) {
            $node = $node[0];
        }

        $num = $node[$this->options['rgt']] + 1 - $node[$this->options['lft']];
        Db::query("UPDATE `$table` SET `{$this->options['lft']}`=`{$this->options['lft']}`+$num WHERE `{$this->options['lft']}`>{$parent[$this->options['rgt']]} AND `{$this->options['node_status']}`=0");
        Db::query("UPDATE `$table` SET `{$this->options['rgt']}`=`{$this->options['rgt']}`+$num WHERE `{$this->options['rgt']}`>={$parent[$this->options['rgt']]} AND `{$this->options['node_status']}`=0");
        print_r(Db::getLastSql());

        $num2 = $parent['rgt'] - $node[$this->options['lft']];
        Db::query("UPDATE `$table` SET `{$this->options['lft']}`=`{$this->options['lft']}`+$num2, `{$this->options['rgt']}`=`{$this->options['rgt']}`+$num2, `{$this->options['node_status']}`=0 WHERE `{$this->options['lft']}`>={$node[$this->options['lft']]} AND `{$this->options['rgt']}`<={$node[$this->options['rgt']]} AND `{$this->options['node_status']}`=1");
        Db::query("UPDATE `$table` SET `{$this->options['parent_id']}`=$parent_node_id WHERE `{$this->options['pk']}`=$node_id");
    }

    /**
     * 删除节点
     */
    public function removeNode($node_id)
    {
        $table = $this->options['table'];
        $pk = $this->options['pk'];
        $rgt = $this->options['rgt'];
        $lft = $this->options['lft'];

        // 取出子节点的信息
        if ($node = Db::query("SELECT * FROM `$table` WHERE `$pk`=$node_id")) {
            $node = $node[0];
        }
        $num = $node[$rgt] + 1 - $node[$lft];

        //Db::query("DELETE FROM `$table` WHERE {$lft}>={$node[$lft]} AND　{$rgt}<={$node[$rgt]}");
        Db::query("UPDATE `$table` SET `{$this->options['node_status']}`=1 WHERE {$lft}>={$node[$lft]} AND {$rgt}<={$node[$rgt]}");

        Db::query("UPDATE `$table` SET `{$this->options['rgt']}`=`{$this->options['rgt']}`-$num WHERE {$this->options['rgt']}>{$node[$this->options['rgt']]} AND `{$this->options['node_status']}`=0");
        Db::query("UPDATE `$table` SET `{$this->options['lft']}`=`{$this->options['lft']}`-$num WHERE {$this->options['lft']}>{$node[$this->options['lft']]} AND `{$this->options['node_status']}`=0");
    }

    /**
     * 移动节点
     */
    public function moveNode($parent_node_id, $node_id)
    {
        $this->removeNode($node_id);
        $this->installNode($node_id, $parent_node_id);
    }
}
上面的类删除使用的是软删除下面的是硬删除：使用上面的类需要几个字段lft默认值1，rgt默认值2，node_status默认值1
public function userLftRgtDelete($id)
    {
        $data = $this->where('id', $id)->field('lft,rgt')->find()->toArray();
        $middle = $data['rgt'] - $data['lft'] + 1;
        $this->where('lft', 'between', [$data['lft'], $data['rgt']])->delete();
        $this->execute("UPDATE `user` SET rgt = rgt-{$middle} WHERE rgt > {$data['rgt']}");
        $data = $this->execute("UPDATE `user` SET lft = lft-{$middle} WHERE lft > {$data['rgt']}");
        return $data;
    }

全是干货，网上的那些复制了一百遍的我真的被坑了
