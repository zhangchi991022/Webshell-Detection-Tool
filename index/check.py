# coding:utf-8
from __future__ import print_function

import os
import numpy as np
from utils import load_php_opcode, recursion_load_php_file_opcode
import tflearn

from tflearn.layers.core import input_data, dropout, fully_connected
from tflearn.layers.conv import conv_1d, global_max_pool
from tflearn.layers.merge_ops import merge
from tflearn.layers.estimator import regression

import tensorflow as tf

from django.core.cache import cache

os.environ['CUDA_VISIBLE_DEVICES'] = '2'
max_document_length = 100


def checkfile(filename):
    php_file_name = filename
    print('Checking the file ' + php_file_name)

    # 之前的数据
    white_file_list = cache.get('white_list')
    black_file_list = cache.get('black_list')
    if not white_file_list  and not black_file_list :
        black_file_list = []
        white_file_list = []
        with open('C:\\Users\\FireFly\\Desktop\\mysite\\index\\black_opcodes.txt', 'r') as f:
            for line in f:
                black_file_list.append(line.strip('\n'))

        with open('C:\\Users\\FireFly\\Desktop\\mysite\\index\\white_opcodes.txt', 'r') as f:
            for line in f:
                white_file_list.append(line.strip('\n'))

        cache.set('white_list', white_file_list, 60 * 60)
        cache.set('black_list', black_file_list, 60 * 60)

    all_token = white_file_list + black_file_list

    # 准备数据
    token = load_php_opcode(php_file_name)
    all_token.append(token)
    x = all_token

    vp = tflearn.data_utils.VocabularyProcessor(max_document_length=max_document_length,
                                                min_frequency=0,
                                                vocabulary=None,
                                                tokenizer_fn=None)
    x = vp.fit_transform(x, unused_y=None)
    x = np.array(list(x))

    # end 准备数据
    tf.reset_default_graph()

    # Building convolutional network
    network = input_data(shape=[None, max_document_length], name='input')
    network = tflearn.embedding(network, input_dim=1000000, output_dim=128)
    branch1 = conv_1d(network, 128, 3, padding='valid', activation='relu', regularizer="L2")
    branch2 = conv_1d(network, 128, 4, padding='valid', activation='relu', regularizer="L2")
    branch3 = conv_1d(network, 128, 5, padding='valid', activation='relu', regularizer="L2")
    network = merge([branch1, branch2, branch3], mode='concat', axis=1)
    network = tf.expand_dims(network, 2)
    network = global_max_pool(network)
    network = dropout(network, 0.8)
    network = fully_connected(network, 2, activation='softmax')
    network = regression(network, optimizer='adam', learning_rate=0.001,
                         loss='categorical_crossentropy', name='target')

    model = tflearn.DNN(network, tensorboard_verbose=0)

    model.load("save/cnn.tfl")

    # clf = joblib.load('save/mlp.pkl')
    y_p = model.predict(x[-1:])

    return y_p


'''
    if y_p[0][0] > 0.5:
        print('Not Webshell')
    else:
        print('Webshell!')
'''
